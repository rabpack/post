<?php

namespace Vendor\Shipping;

class Invoice
{
    protected array $data = [];

    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    public function setOrigin(string $city, string $province = null): self
    {
        $this->data['origin'] = ['city' => $city, 'province' => $province];
        return $this;
    }

    public function setDestination(string $city, string $province = null): self
    {
        $this->data['destination'] = ['city' => $city, 'province' => $province];
        return $this;
    }

    public function setWeight(float $weight): self
    {
        $this->data['weight'] = $weight;
        return $this;
    }

    public function setDimensions(float $length, float $width, float $height): self
    {
        $this->data['dimensions'] = compact('length', 'width', 'height');
        return $this;
    }

    public function setValue(int $value): self
    {
        $this->data['value'] = $value;
        return $this;
    }

    public function setServiceType(string $type): self
    {
        $this->data['service_type'] = $type;
        return $this;
    }

    public function toArray(): array
    {
        return $this->data;
    }

    public function get(string $key, $default = null)
    {
        return $this->data[$key] ?? $default;
    }

    public function __get(string $key)
    {
        return $this->get($key);
    }
}
