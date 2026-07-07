<?php

namespace Vendor\Shipping\Contracts;

use Vendor\Shipping\Invoice;

interface DriverInterface
{
    /**
     * تنظیمات درایور را از آرایه تنظیم می‌کند
     */
    public function setConfig(array $config): self;

    /**
     * محاسبه هزینه ارسال
     * @return array { 'cost' => int, 'currency' => string, 'details' => array }
     */
    public function calculate(Invoice $invoice): array;

    /**
     * دریافت نام درایور
     */
    public function getName(): string;
}
