<?php

namespace Vendor\Shipping;

use Vendor\Shipping\Contracts\ManagerInterface;
use Vendor\Shipping\Contracts\ShippingInterface;
use Vendor\Shipping\Exceptions\InvalidInvoiceException;

class Shipping implements ShippingInterface
{
    protected ManagerInterface $manager;
    protected ?Invoice $invoice = null;

    public function __construct(ManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function invoice(Invoice $invoice): self
    {
        $this->invoice = $invoice;
        return $this;
    }

    public function via(string $driver): array
    {
        if (!$this->invoice) {
            throw new InvalidInvoiceException("Invoice not set. Use invoice() method first.");
        }

        $this->validateInvoice($this->invoice);

        $driverInstance = $this->manager->driver($driver);
        return $driverInstance->calculate($this->invoice);
    }

    public function manager(): ManagerInterface
    {
        return $this->manager;
    }

    protected function validateInvoice(Invoice $invoice): void
    {
        if (empty($invoice->get('origin'))) {
            throw new InvalidInvoiceException("Origin information is required.");
        }

        if (empty($invoice->get('destination'))) {
            throw new InvalidInvoiceException("Destination information is required.");
        }

        if ($invoice->get('weight', 0) <= 0) {
            throw new InvalidInvoiceException("Weight must be greater than zero.");
        }
    }
}
