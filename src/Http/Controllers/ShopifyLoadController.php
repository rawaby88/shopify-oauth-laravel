<?php

namespace ShopifyOauthLaravel\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use joymendonca\ShopifyOauthLaravel\Facades\ShopifyOauthLaravel;

class ShopifyLOadController extends Controller
{
    public function load(): RedirectResponse
    {
        if(request('code') && request('shop'))
        {
            $client_id = Config::get('shopify-oauth-laravel.client_id');
            $client_secret = Config::get('shopify-oauth-laravel.client_secret');
            $app_home_url = Config::get('shopify-oauth-laravel.app_home_url');
            $response = Http::post('https://' . request('shop') . '/admin/oauth/access_token?client_id=' . $client_id . '&client_secret=' . $client_secret . '&code=' . request('code'));
            if(isset($response['access_token'])){
                $store = $this->getUserModelClass()::query()->updateOrCreate([
                    'store_url' => request('shop')
                ],[
                    'store_url' => request('shop'),
                    'access_token' => $response['access_token'],
                    'scope' => $response['scope'],
                ]);

                $user = $this->saveUserIfNotExist(request('shop'));

                if (isset($user->id) && isset($store->id)) {
                    if ($this->assignUserToStore($user->id, $store->id)) {
                        Auth::login($user);
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

    protected function saveUserIfNotExist($store_url)
    {
        return $this->getUserModelClass()::query()->firstOrCreate([
            'email' => 'store@'.$store_url
        ],
        [
            'email' => 'store@'.$store_url,
            'store_url' => $store_url
        ]);
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

    protected function getUserModelClass(): string
    {
        return Config::get('auth.providers.users.model');
    }

    protected function getStoreModelClass(): string
    {
        return Config::get('shopify-oauth-laravel.models.store_model');
    }
}