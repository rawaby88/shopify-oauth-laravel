<?php

namespace joymendonca\ShopifyOauthLaravel\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class Store extends Model
{
    protected $fillable = [
        'store_url',
        'access_token',
        'scope',
    ];

    public function getTable()
    {
        return Config::get('shopify-oauth-laravel.tables.stores', parent::getTable());
    }
}