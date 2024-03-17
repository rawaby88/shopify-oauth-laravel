# Shopify OAuth Laravel Package

This package provides a convenient way to integrate Shopify OAuth authentication into your Laravel application. It simplifies the process of setting up OAuth with Shopify, allowing you to focus on building your application's features rather than dealing with the intricacies of authentication.

## Installation

You can install this package via Composer. Run the following command in your terminal:

```bash
composer require joymendonca/shopify-oauth-laravel
```

## Configuration

After installing the package, you'll need to publish and run the migrations with:

```bash
php artisan vendor:publish --tag="shopify-oauth-laravel-migrations"
php artisan migrate
```

You can also publish the config file using:

```bash
php artisan vendor:publish --tag="shopify-oauth-laravel-config"
```

You will now need to setup the environment variables in your .env file:

```bash
APP_URL="https://your-website.com"                    #The base url for your website
SHOPIFY_CLIENT_ID="your-shopify-client-id"            #Shopify App Client ID
SHOPIFY_CLIENT_SECRET="your-shopify-client-secret"    #Shopify App Client Secret
SHOPIFY_SCOPES="read_products,write_products"         #Shopify App Scopes Needed
SHOPIFY_APP_HOME_URL='/'                              #URL you want the user to get redirected to when the launch the app
```

You can register the routes using the code below in web.php:

```php
use joymendonca\ShopifyOauthLaravel\ShopifyOAuthLaravelRoutes;

ShopifyOAuthLaravelRoutes::register();
```
Make sure the app install url registered in your shopify app is "https://your-website.com/shopify-app-auth/install" and the redirect url is "https://your-website.com/shopify-app-auth/load"
## Usage

Once the package is installed and configured, you can start using Shopify OAuth in your Laravel application.

You can get the access token and store url of the logged in as below:

```php
use joymendonca\ShopifyOauthLaravel\Facades\ShopifyOauthLaravel;

$access_token = ShopifyOauthLaravel::getStoreAccessToken();
$store_url = ShopifyOauthLaravel::getStoreUrl();
```

