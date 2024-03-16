<?php

namespace joymendonca\ShopifyOauthLaravel;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

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
            ->hasMigration('update_users_table')
            ->hasMigration('create_stores_table')
            ->hasMigration('create_store_has_users_table');
    }
}
