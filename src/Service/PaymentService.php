<?php

declare(strict_types=1);

namespace Econt\EcontApi\Service;

use Econt\EcontApi\Client\EcontClientInterface;
use Econt\EcontApi\Model\Response\EcontResponse;
use Econt\EcontApi\Service\Contract\PaymentServiceInterface;

class PaymentService implements PaymentServiceInterface
{
    private const SERVICE_SET_PAY = 'Services.Payments.setPay';
    private const SERVICE_GET_PAY_STATUS = 'Services.Payments.getPayStatus';
    private const SERVICE_CREATE_INVOICE = 'Services.Payments.createInvoice';
    private const SERVICE_GET_INVOICE = 'Services.Payments.getInvoice';

    private EcontClientInterface $client;

    public function __construct(EcontClientInterface $client)
    {
        $this->client = $client;
    }

    public function setPay(string $billGuid, string $paymentMethod, ?float $amount = null): EcontResponse
    {
        $data = [
            'bill_guid' => $billGuid,
            'pay_type' => $paymentMethod,
        ];

        if ($amount !== null) {
            $data['amount'] = $amount;
        }

        return $this->client->request(self::SERVICE_SET_PAY, $data);
    }

    public function getPayStatus(string $billGuid): EcontResponse
    {
        return $this->client->request(self::SERVICE_GET_PAY_STATUS, [
            'bill_guid' => $billGuid,
        ]);
    }

    public function createInvoice(string $billGuid): EcontResponse
    {
        return $this->client->request(self::SERVICE_CREATE_INVOICE, [
            'bill_guid' => $billGuid,
        ]);
    }

    public function getInvoice(string $invoiceId): EcontResponse
    {
        return $this->client->request(self::SERVICE_GET_INVOICE, [
            'invoice_id' => $invoiceId,
        ]);
    }
}