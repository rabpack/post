<?php

namespace Vendor\Shipping\Drivers;

use Vendor\Shipping\Contracts\DriverInterface;
use Vendor\Shipping\Invoice;
use Vendor\Shipping\Exceptions\ApiException;
use Illuminate\Support\Facades\Http;

abstract class AbstractDriver implements DriverInterface
{
    protected array $config = [];
    protected string $name;

    public function setConfig(array $config): self
    {
        $this->config = $config;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * ارسال درخواست HTTP به API
     * @throws ApiException
     */
    protected function request(string $method, string $endpoint, array $data = []): array
    {
        $baseUrl = rtrim($this->config['base_url'] ?? '', '/');
        $url = $baseUrl . '/' . ltrim($endpoint, '/');

        try {
            $response = Http::withHeaders($this->getHeaders())
                ->timeout($this->config['timeout'] ?? 30)
                ->$method($url, $data);

            if (!$response->successful()) {
                throw new ApiException(
                    "API responded with status: " . $response->status(),
                    $response->status(),
                    $response->json() ?? []
                );
            }

            return $response->json();
        } catch (\Illuminate\Http\Client\RequestException $e) {
            throw new ApiException(
                "Request failed: " . $e->getMessage(),
                $e->getCode(),
                ['trace' => $e->getTraceAsString()]
            );
        } catch (\Exception $e) {
            throw new ApiException(
                "Unexpected error: " . $e->getMessage(),
                500,
                ['original' => $e->getMessage()]
            );
        }
    }

    /**
     * هدرهای احراز هویت
     */
    protected function getHeaders(): array
    {
        $headers = ['Accept' => 'application/json'];

        if (!empty($this->config['api_key'])) {
            $headers['Authorization'] = 'Bearer ' . $this->config['api_key'];
        }

        if (!empty($this->config['client_id']) && !empty($this->config['secret'])) {
            $headers['X-Client-Id'] = $this->config['client_id'];
            $headers['X-Secret'] = $this->config['secret'];
        }

        return $headers;
    }

    abstract public function calculate(Invoice $invoice): array;
}
