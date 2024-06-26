<?php

namespace joymendonca\ShopifyOauthLaravel;

use Closure;
use Exception;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;

class ShopifyOauthLaravel
{
    private Closure $installCallback;

    private Closure $loadCallback;

    public function setStoreUrl(string $store_url): void
    {
        Session::put($this->getStoreUrlSessionKey(), $store_url);
    }

    public function getStoreUrl(): string|false
    {
        return Session::get($this->getStoreUrlSessionKey(), false);
    }

    public function getStoreAccessToken(): string|bool
    {
        if (!($store_url = $this->getStoreUrl()))
            throw new Exception('Store url is not set. Please set store url using setStoreUrl method.');

        $store = $this->getStoreModelClass()::query()
            ->select(['id', 'access_token'])
            ->where('store_url', $store_url)
            ->first();
        if (!$store)
            return false;

        return (string)$store->access_token;
    }

    public function callInstallCallback($user, $store)
    {
        if (isset($this->installCallback))
            ($this->installCallback)($user, $store);
    }

    public function callLoadCallback($user, $store)
    {
        if (isset($this->loadCallback))
            ($this->loadCallback)($user, $store);
    }

    public function setInstallCallback(Closure $installCallback): void
    {
        $this->installCallback = $installCallback;
    }

    public function setLoadCallback(Closure $loadCallback): void
    {
        $this->loadCallback = $loadCallback;
    }

    private function getStoreUrlSessionKey(): string
    {
        return $this->getSessionKey() . '.store_url_' . sha1(static::class);
    }

    private function getSessionKey(): string
    {
        return Config::get('shopify-oauth-laravel.session_key', 'shopify_auth');
    }

    protected function getStoreModelClass(): string
    {
        return Config::get('shopify-oauth-laravel.models.store_model');
    }
}
