<?php

namespace joymendonca\ShopifyOauthLaravel\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use joymendonca\ShopifyOauthLaravel\Facades\ShopifyOauthLaravel;

class ShopifyLoadController extends Controller
{
    public function load(): RedirectResponse
    {
        if(request('code') && request('shop') && request('hmac'))
        {
            if(!$this->validateResponse(request('hmac'), request()->getQueryString())){
                return redirect()->route('error')->with([
                    'message' => 'Invalid Request'
                ]);
            }

            $client_id = Config::get('shopify-oauth-laravel.client_id');
            $client_secret = Config::get('shopify-oauth-laravel.client_secret');
            $app_home_url = Config::get('shopify-oauth-laravel.app_home_url');
            $response = Http::post('https://' . request('shop') . '/admin/oauth/access_token?client_id=' . $client_id . '&client_secret=' . $client_secret . '&code=' . request('code'));
            if(isset($response['access_token'])){
                $store = $this->getStoreModelClass()::query()->updateOrCreate([
                    'store_url' => request('shop')
                ],[
                    'store_url' => request('shop'),
                    'access_token' => $response['access_token'],
                    'scope' => $response['scope'],
                ]);

                if(!$user = $this->getUserModelClass()::query()->where('store_url', request('shop'))->first())
                    $user = $this->createUser($store);

                if (isset($user->id) && isset($store->id)) {
                    if ($this->assignUserToStore($user->id, $store->id)) {
                        Auth::login($user);
                        if(!$store->uninstall_webhook)
                            $this->createUninstallWebhook($store);
                        ShopifyOauthLaravel::setStoreUrl($store->store_url);
                        ShopifyOauthLaravel::callInstallCallback($user, $store);
                        ShopifyOauthLaravel::callLoadCallback($user, $store);
                    }
                }
                return redirect($app_home_url);
            } else {
                return redirect()->route('error')->with([
                    'message' => 'Access token not received'
                ]);
            }
        } else {
            return redirect()->route('error')->with([
                'message' => 'Something went wrong'
            ]);
        }
    }

    protected function validateResponse($hmac, $query_string)
    {
        $client_secret = Config::get('shopify-oauth-laravel.client_secret');
        $query_without_hmac = str_replace('hmac='. $hmac . '&', '', $query_string);
        $calculated_hmac = hash_hmac('sha256', $query_without_hmac, $client_secret);
        return $calculated_hmac == $hmac;
    }

    protected function createUser($store)
    {
        $api_version = Config::get('shopify-oauth-laravel.api_version');
        $response = Http::withHeader('X-Shopify-Access-Token', $store->access_token)->get('https://' . $store->store_url . '/admin/api/' . $api_version . '/shop.json');
        
        if(isset($response['shop'])){
            return $this->getUserModelClass()::query()->updateOrCreate([
                'email' => isset($response['shop']['email']) ? $response['shop']['email'] : 'store@' . $store->store_url,
            ], [
                'email' => isset($response['shop']['email']) ? $response['shop']['email'] : 'store@' . $store->store_url,
                'name' => isset($response['shop']['name']) ? $response['shop']['name'] : null,
                'store_url' => $store->store_url
            ]);
        }
    }
    protected function assignUserToStore($user_id, $store_id): bool
    {
        $store_has_users = Config::get('shopify-oauth-laravel.tables.store_has_users');
        if (DB::table($store_has_users)
            ->where('store_id', $store_id)
            ->where('user_id', $user_id)
            ->exists())
            return true;

        return DB::table($store_has_users)->insert([
            'store_id' => $store_id,
            'user_id' => $user_id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    protected function createUninstallWebhook($store)
    {
        $data['webhook'] = [
            'address' => url('/shopify-app-auth/uninstall'),
            'topic' => 'app/uninstalled',
            'format' => 'json',
        ];
        $api_version = Config::get('shopify-oauth-laravel.api_version');
        $response = Http::withHeader('X-Shopify-Access-Token', $store->access_token)->post('https://' . $store->store_url . '/admin/api/' . $api_version . '/webhooks.json', $data);
        
        if(isset($response['webhook']['id'])){
            $this->getStoreModelClass()::query()->where('id',$store->id)->update([
                'uninstall_webhook' => $response['webhook']['id']
            ]);
        }
    }

    protected function getUserModelClass(): string
    {
        return Config::get('auth.providers.users.model');
    }

    protected function getStoreModelClass(): string
    {
        return Config::get('shopify-oauth-laravel.models.store_model');
    }
}