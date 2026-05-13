<?php

declare(strict_types=1);

namespace Econt\EcontApi\Service\Contract;

use Econt\EcontApi\Model\Response\EcontResponse;

interface PaymentServiceInterface
{
    public function setPay(string $billGuid, string $paymentMethod, ?float $amount = null): EcontResponse;

    public function getPayStatus(string $billGuid): EcontResponse;

    public function createInvoice(string $billGuid): EcontResponse;

    public function getInvoice(string $invoiceId): EcontResponse;
}