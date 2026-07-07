<?php

namespace Vendor\Shipping\Exceptions;

use Exception;

class InvalidInvoiceException extends Exception
{
    public function __construct(string $message = "Invoice data is invalid or incomplete.")
    {
        parent::__construct($message);
    }
}
