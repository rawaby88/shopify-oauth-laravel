<?php

namespace joymendonca\ShopifyOauthLaravel\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Config;

class ShopifyInstallController extends Controller
{
    public function install(): RedirectResponse
    {
        if(request('host') && request('shop'))
        {
            $scopes = Config::get('shopify-oauth-laravel.scopes');
            $client_id = Config::get('shopify-oauth-laravel.client_id');
            $redirect_url = Config::get('shopify-oauth-laravel.base_url') . '/shopify-app-auth/load';
            $url = 'https://' . request('shop') . '/admin/oauth/authorize?client_id=' . $client_id . '&scope=' . $scopes . '&redirect_uri=' . $redirect_url . '&host=' . request('host');
            return redirect($url);
        } else {
            return redirect()->route('error')->with([
                'message' => 'Something went wrong'
            ]);
        }
    }
}