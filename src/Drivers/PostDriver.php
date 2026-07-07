<?php

namespace Vendor\Shipping\Drivers;

use Vendor\Shipping\Invoice;
use Vendor\Shipping\Exceptions\ApiException;

class PostDriver extends AbstractDriver
{
    protected string $name = 'post';

    public function calculate(Invoice $invoice): array
    {
        // اعتبارسنجی اولیه داده‌ها
        $this->validateInvoice($invoice);

        // ساخت payload مخصوص API پست
        $payload = $this->buildPayload($invoice);

        try {
            // ارسال درخواست به API پست (فرضی)
            $response = $this->request('post', 'v1/shipping/calculate', $payload);
        } catch (\Exception $e) {
            throw new ApiException(
                "Post API request failed: " . $e->getMessage(),
                500,
                ['original_exception' => $e->getMessage()]
            );
        }

        // پردازش پاسخ (ساختار فرضی برای پست)
        return $this->formatResponse($response);
    }

    /**
     * اعتبارسنجی فاکتور
     */
    protected function validateInvoice(Invoice $invoice): void
    {
        if (empty($invoice->get('origin'))) {
            throw new \Vendor\Shipping\Exceptions\InvalidInvoiceException("Origin (mabda) is required.");
        }

        if (empty($invoice->get('destination'))) {
            throw new \Vendor\Shipping\Exceptions\InvalidInvoiceException("Destination (maghsad) is required.");
        }

        if ($invoice->get('weight', 0) <= 0) {
            throw new \Vendor\Shipping\Exceptions\InvalidInvoiceException("Weight must be greater than zero.");
        }
    }

    /**
     * ساخت Payload درخواست
     */
    protected function buildPayload(Invoice $invoice): array
    {
        return [
            'mabda' => $invoice->get('origin'),
            'maghsad' => $invoice->get('destination'),
            'vazn' => $invoice->get('weight'),
            'abad' => $invoice->get('dimensions'),
            'arzesh' => $invoice->get('value', 0),
            'noe_servis' => $invoice->get('service_type', 'normal'),
        ];
    }

    /**
     * فرمت‌دهی پاسخ API پست
     */
    protected function formatResponse(array $response): array
    {
        return [
            'cost'      => $response['hazineh'] ?? $response['price'] ?? 0,
            'currency'  => $response['currency'] ?? 'IRR',
            'details'   => $response['details'] ?? [],
            'raw'       => $response,
        ];
    }
}
