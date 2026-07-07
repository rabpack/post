<?php

namespace Vendor\Shipping\Providers;

use Illuminate\Support\ServiceProvider;
use Vendor\Shipping\Shipping;
use Vendor\Shipping\Drivers\Manager;
use Vendor\Shipping\Contracts\ManagerInterface;

class ShippingServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/shipping.php',
            'shipping'
        );

        // اتصال اینترفیس به پیاده‌سازی
        $this->app->bind(ManagerInterface::class, function ($app) {
            return new Manager($app['config']['shipping']);
        });

        // ثبت سرویس اصلی Shipping
        $this->app->singleton('shipping', function ($app) {
            return new Shipping($app->make(ManagerInterface::class));
        });
    }

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../../config/shipping.php' => config_path('shipping.php'),
        ], 'shipping-config');
    }
}
