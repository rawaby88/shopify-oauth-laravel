<?php

namespace joymendonca\ShopifyOauthLaravel\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \joymendonca\ShopifyOauthLaravel\ShopifyOauthLaravel
 */
class ShopifyOauthLaravel extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \joymendonca\ShopifyOauthLaravel\ShopifyOauthLaravel::class;
    }
}
