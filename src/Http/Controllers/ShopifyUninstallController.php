<?php

namespace joymendonca\ShopifyOauthLaravel\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Config;


class ShopifyUninstallController extends Controller
{
    public function uninstall(): void
    {
        if(request('myshopify_domain'))
        {
            $data = request()->getContent();
            $client_secret = Config::get('shopify-oauth-laravel.client_secret');
            $calculated_hmac = base64_encode(hash_hmac('sha256', $data, $client_secret, true));
            if($calculated_hmac != request()->header('x-shopify-hmac-sha256'))
                return;

            $this->getStoreModelClass()::query()->where('store_url', request('myshopify_domain'))->delete();
            $this->getUserModelClass()::query()->where('store_url', request('myshopify_domain'))->delete();
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