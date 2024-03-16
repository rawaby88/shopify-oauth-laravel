<?php

namespace joymendonca\ShopifyOauthLaravel;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use joymendonca\ShopifyOauthLaravel\Commands\ShopifyOauthLaravelCommand;

class ShopifyOauthLaravelServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('shopify-oauth-laravel')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_shopify-oauth-laravel_table')
            ->hasCommand(ShopifyOauthLaravelCommand::class);
    }
}
