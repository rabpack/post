<?php

namespace Vendor\Shipping\Drivers;

use Vendor\Shipping\Invoice;

class TipaxDriver extends AbstractDriver
{
    protected string $name = 'tipax';

    public function calculate(Invoice $invoice): array
    {
        // ۱. دریافت توکن احراز هویت (در صورت نیاز)
        $token = $this->authenticate();

        // ۲. آماده‌سازی داده‌های درخواست
        $payload = $this->buildPayload($invoice);

        // ۳. ارسال درخواست به API محاسبه هزینه
        $response = $this->request('post', 'api/shipping/calculate', $payload);

        // ۴. پردازش پاسخ
        return $this->formatResponse($response);
    }

    /**
     * احراز هویت و دریافت توکن
     */
    protected function authenticate(): ?string
    {
        if (empty($this->config['username']) || empty($this->config['password'])) {
            return null;
        }

        $response = $this->request('post', 'core/connect/token', [
            'ClientName' => $this->config['client_name'] ?? '',
            'ClientId'   => $this->config['client_id'] ?? '',
            'Secret'     => $this->config['secret'] ?? '',
            'Scope'      => $this->config['scope'] ?? '',
            'UserName'   => $this->config['username'],
            'Password'   => $this->config['password'],
        ]);

        return $response['access_token'] ?? null;
    }

    /**
     * ساخت Payload درخواست
     */
    protected function buildPayload(Invoice $invoice): array
    {
        $payload = [];

        // اطلاعات مبدأ
        if ($origin = $invoice->get('origin')) {
            $payload['origin_city'] = $origin['city'] ?? '';
            $payload['origin_province'] = $origin['province'] ?? '';
        }

        // اطلاعات مقصد
        if ($destination = $invoice->get('destination')) {
            $payload['destination_city'] = $destination['city'] ?? '';
            $payload['destination_province'] = $destination['province'] ?? '';
        }

        // وزن (به کیلوگرم)
        $payload['weight'] = $invoice->get('weight', 0);

        // ابعاد (به سانتی‌متر)
        if ($dimensions = $invoice->get('dimensions')) {
            $payload['length'] = $dimensions['length'] ?? 0;
            $payload['width']  = $dimensions['width'] ?? 0;
            $payload['height'] = $dimensions['height'] ?? 0;
        }

        // ارزش کالا
        $payload['value'] = $invoice->get('value', 0);

        // نوع سرویس (normal, express, same_day, ...)
        $payload['service_type'] = $invoice->get('service_type', 'normal');

        return $payload;
    }

    /**
     * فرمت‌دهی پاسخ API
     */
    protected function formatResponse(array $response): array
    {
        return [
            'cost'      => $response['cost'] ?? $response['price'] ?? 0,
            'currency'  => $response['currency'] ?? 'IRR',
            'details'   => $response['details'] ?? [],
            'raw'       => $response,
        ];
    }
}
