<?php

declare(strict_types=1);

namespace Econt\EcontApi\Service\Contract;

use Econt\EcontApi\Model\Response\EcontResponse;
use Econt\EcontApi\Model\Shipment\Shipment;

interface ShipmentServiceInterface
{
    public function createBill(Shipment $shipment): EcontResponse;

    public function confirmBill(string $billGuid): EcontResponse;

    public function cancelBill(string $billGuid): EcontResponse;

    public function updateBill(Shipment $shipment, string $billGuid): EcontResponse;

    public function trackBill(string $billGuid): EcontResponse;

    public function requestCourier(
        string $scheduleDate,
        ?string $scheduleTimeFrom = null,
        ?string $scheduleTimeTo = null
    ): EcontResponse;

    public function getShipmentCalculation(Shipment $shipment): EcontResponse;
}