<?php

namespace Vendor\Shipping\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Vendor\Shipping\Shipping invoice(\Vendor\Shipping\Invoice $invoice)
 * @method static array via(string $driver)
 * @method static \Vendor\Shipping\Drivers\Manager manager()
 * @method static \Vendor\Shipping\Contracts\DriverInterface driver(string $name)
 */
class Shipping extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'shipping';
    }
}
