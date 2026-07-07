<?php

namespace Vendor\Shipping\Drivers;

use Vendor\Shipping\Contracts\DriverInterface;
use Vendor\Shipping\Contracts\ManagerInterface;
use Vendor\Shipping\Exceptions\DriverNotFoundException;

class Manager implements ManagerInterface
{
    protected array $drivers = [];
    protected array $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function driver(string $name): DriverInterface
    {
        $name = strtolower($name);

        if (isset($this->drivers[$name])) {
            return $this->drivers[$name];
        }

        $driver = $this->createDriver($name);
        $this->drivers[$name] = $driver;

        return $driver;
    }

    protected function createDriver(string $name): DriverInterface
    {
        $driverClass = $this->getDriverClass($name);

        if (!class_exists($driverClass)) {
            throw new DriverNotFoundException($name);
        }

        $driver = new $driverClass();
        $driver->setConfig($this->config['drivers'][$name] ?? []);

        return $driver;
    }

    protected function getDriverClass(string $name): string
    {
        $map = [
            'tipax' => TipaxDriver::class,
            'post'  => PostDriver::class,
        ];

        return $map[$name] ?? '\\Vendor\\Shipping\\Drivers\\' . ucfirst($name) . 'Driver';
    }

    public function drivers(): array
    {
        return array_keys($this->config['drivers'] ?? []);
    }
}
