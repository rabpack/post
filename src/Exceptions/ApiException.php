<?php

namespace Vendor\Shipping\Exceptions;

use Exception;

class ApiException extends Exception
{
    protected int $statusCode;
    protected array $responseBody;

    public function __construct(string $message, int $statusCode = 500, array $responseBody = [])
    {
        parent::__construct($message, $statusCode);
        $this->statusCode = $statusCode;
        $this->responseBody = $responseBody;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getResponseBody(): array
    {
        return $this->responseBody;
    }
}
