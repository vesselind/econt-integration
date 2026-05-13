<?php

declare(strict_types=1);

namespace Econt\EcontApi\Service;

use Econt\EcontApi\Client\EcontClientInterface;
use Econt\EcontApi\Model\Response\EcontResponse;
use Econt\EcontApi\Model\Shipment\Shipment;
use Econt\EcontApi\Service\Contract\ShipmentServiceInterface;

class ShipmentService implements ShipmentServiceInterface
{
    private const SERVICE_CREATE_BILL = 'Services.Shipments.createBill';
    private const SERVICE_CONFIRM_BILL = 'Services.Shipments.confirmBill';
    private const SERVICE_CANCEL_BILL = 'Services.Shipments.cancelBill';
    private const SERVICE_UPDATE_BILL = 'Services.Shipments.updateBill';
    private const SERVICE_TRACK_BILL = 'Services.Shipments.trackBill';
    private const SERVICE_REQUEST_COURIER = 'Services.Shipments.requestCourier';
    private const SERVICE_CALCULATION = 'Services.Shipments.getShipmentCalculation';

    private EcontClientInterface $client;

    public function __construct(EcontClientInterface $client)
    {
        $this->client = $client;
    }

    public function createBill(Shipment $shipment): EcontResponse
    {
        $data = $shipment->toArray();

        if (isset($data['sender']) && $data['sender'] instanceof \Econt\EcontApi\Model\AbstractModel) {
            $data['sender'] = $data['sender']->toArray();
        }
        if (isset($data['receiver']) && $data['receiver'] instanceof \Econt\EcontApi\Model\AbstractModel) {
            $data['receiver'] = $data['receiver']->toArray();
        }

        return $this->client->request(self::SERVICE_CREATE_BILL, $data);
    }

    public function confirmBill(string $billGuid): EcontResponse
    {
        return $this->client->request(self::SERVICE_CONFIRM_BILL, [
            'bill_guid' => $billGuid,
        ]);
    }

    public function cancelBill(string $billGuid): EcontResponse
    {
        return $this->client->request(self::SERVICE_CANCEL_BILL, [
            'bill_guid' => $billGuid,
        ]);
    }

    public function updateBill(Shipment $shipment, string $billGuid): EcontResponse
    {
        $data = $shipment->toArray();
        $data['bill_guid'] = $billGuid;

        if (isset($data['sender']) && $data['sender'] instanceof \Econt\EcontApi\Model\AbstractModel) {
            $data['sender'] = $data['sender']->toArray();
        }
        if (isset($data['receiver']) && $data['receiver'] instanceof \Econt\EcontApi\Model\AbstractModel) {
            $data['receiver'] = $data['receiver']->toArray();
        }

        return $this->client->request(self::SERVICE_UPDATE_BILL, $data);
    }

    public function trackBill(string $billGuid): EcontResponse
    {
        return $this->client->request(self::SERVICE_TRACK_BILL, [
            'bill_guid' => $billGuid,
        ]);
    }

    public function requestCourier(
        string $scheduleDate,
        ?string $scheduleTimeFrom = null,
        ?string $scheduleTimeTo = null
    ): EcontResponse {
        $data = [
            'schedule_date' => $scheduleDate,
        ];

        if ($scheduleTimeFrom !== null) {
            $data['schedule_time_from'] = $scheduleTimeFrom;
        }

        if ($scheduleTimeTo !== null) {
            $data['schedule_time_to'] = $scheduleTimeTo;
        }

        return $this->client->request(self::SERVICE_REQUEST_COURIER, $data);
    }

    public function getShipmentCalculation(Shipment $shipment): EcontResponse
    {
        $data = $shipment->toArray();

        if (isset($data['sender']) && $data['sender'] instanceof \Econt\EcontApi\Model\AbstractModel) {
            $data['sender'] = $data['sender']->toArray();
        }
        if (isset($data['receiver']) && $data['receiver'] instanceof \Econt\EcontApi\Model\AbstractModel) {
            $data['receiver'] = $data['receiver']->toArray();
        }

        return $this->client->request(self::SERVICE_CALCULATION, $data);
    }
}