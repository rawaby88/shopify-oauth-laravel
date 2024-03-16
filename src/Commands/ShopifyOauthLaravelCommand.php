<?php

namespace joymendonca\ShopifyOauthLaravel\Commands;

use Illuminate\Console\Command;

class ShopifyOauthLaravelCommand extends Command
{
    public $signature = 'shopify-oauth-laravel';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
