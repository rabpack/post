<?php

namespace Vendor\Shipping\Contracts;

use Vendor\Shipping\Invoice;

interface ShippingInterface
{
    /**
     * تنظیم فاکتور ارسال
     */
    public function invoice(Invoice $invoice): self;

    /**
     * محاسبه هزینه با درایور مشخص
     * @throws \Vendor\Shipping\Exceptions\InvalidInvoiceException
     * @throws \Vendor\Shipping\Exceptions\DriverNotFoundException
     * @throws \Vendor\Shipping\Exceptions\ApiException
     */
    public function via(string $driver): array;

    /**
     * دریافت مدیریت درایورها
     */
    public function manager(): ManagerInterface;
}
