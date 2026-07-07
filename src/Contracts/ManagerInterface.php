<?php

namespace Vendor\Shipping\Contracts;

interface ManagerInterface
{
    /**
     * دریافت یک درایور بر اساس نام
     * @throws \Vendor\Shipping\Exceptions\DriverNotFoundException
     */
    public function driver(string $name): DriverInterface;

    /**
     * لیست اسامی درایورهای فعال
     */
    public function drivers(): array;
}
