<?php

namespace Vendor\Shipping\Exceptions;

use Exception;

class DriverNotFoundException extends Exception
{
    public function __construct(string $driverName)
    {
        parent::__construct("Driver [{$driverName}] not found or not configured properly.");
    }
}
