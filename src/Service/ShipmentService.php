<?php

declare(strict_types=1);

namespace Econt\EcontApi\Service;

use Econt\EcontApi\Exception\EcontApiException;
use Econt\EcontApi\Exception\EcontNetworkException;
use Econt\EcontApi\Exception\EcontValidationException;
use Econt\EcontApi\Http\HttpAdapterInterface;
use Econt\EcontApi\Model\Result\CancelLabelResult;
use Econt\EcontApi\Model\Result\ConfirmLabelResult;
use Econt\EcontApi\Model\Result\CourierRequestResult;
use Econt\EcontApi\Model\Result\PriceCalculationResult;
use Econt\EcontApi\Model\Result\ShipmentLabelResult;
use Econt\EcontApi\Model\Result\ShipmentTrackingResult;
use Econt\EcontApi\Model\Shipment\CourierRequest;
use Econt\EcontApi\Model\Shipment\ShippingLabel;

/**
 * Service for shipment label lifecycle and courier request operations.
 */
class ShipmentService
{
    public function __construct(
        private readonly HttpAdapterInterface $adapter,
    ) {
    }

    /**
     * Calculate the shipping price without creating a waybill.
     *
     * @throws EcontValidationException
     * @throws EcontApiException
     * @throws EcontNetworkException
     */
    public function calculatePrice(ShippingLabel $label): PriceCalculationResult
    {
        $label->validate();
        $cloned = $label->setMode('calculate');

        $response = $this->adapter->post(
            'Shipments/LabelService.createLabel.json',
            ['label' => $cloned->toArray(), 'mode' => 'calculate'],
        );

        $labelData = $response['label'] ?? $response;

        // Price may be nested under 'price' key or directly on the label object
        if (isset($labelData['price']) && is_array($labelData['price'])) {
            $priceData = $labelData['price'];
        } else {
            // API may return totalPrice/currency directly on the label
            $priceData = $labelData;
        }

        return PriceCalculationResult::fromArray((array) $priceData);
    }

    /**
     * Validate a label without creating a waybill.
     *
     * @throws EcontValidationException
     * @throws EcontApiException
     * @throws EcontNetworkException
     */
    public function validateLabel(ShippingLabel $label): ShipmentLabelResult
    {
        $label->validate();
        $cloned = $label->setMode('validate');

        $response = $this->adapter->post(
            'Shipments/LabelService.createLabel.json',
            ['label' => $cloned->toArray(), 'mode' => 'validate'],
        );

        return ShipmentLabelResult::fromArray($response);
    }

    /**
     * Create a shipping label and get a waybill number.
     *
     * @throws EcontValidationException
     * @throws EcontApiException
     * @throws EcontNetworkException
     */
    public function createLabel(ShippingLabel $label): ShipmentLabelResult
    {
        $label->validate();
        $cloned = $label->setMode('create');

        $response = $this->adapter->post(
            'Shipments/LabelService.createLabel.json',
            ['label' => $cloned->toArray(), 'mode' => 'create'],
        );

        return ShipmentLabelResult::fromArray($response);
    }

    /**
     * Confirm (process) a label by waybill number.
     *
     * @throws EcontApiException
     * @throws EcontNetworkException
     */
    public function confirmLabel(string $waybillNumber): ConfirmLabelResult
    {
        $response = $this->adapter->post(
            'Shipments/LabelService.processLabel.json',
            ['waybillNumber' => $waybillNumber],
        );

        return ConfirmLabelResult::fromArray($response);
    }

    /**
     * Update an existing label.
     *
     * @throws EcontValidationException
     * @throws EcontApiException
     * @throws EcontNetworkException
     */
    public function updateLabel(string $waybillNumber, ShippingLabel $updatedLabel): ShipmentLabelResult
    {
        $updatedLabel->validate();
        $cloned = $updatedLabel->setMode('create');
        $labelArray = $cloned->toArray();
        $labelArray['waybillNumber'] = $waybillNumber;

        $response = $this->adapter->post(
            'Shipments/LabelService.createLabel.json',
            ['label' => $labelArray, 'mode' => 'create'],
        );

        return ShipmentLabelResult::fromArray($response);
    }

    /**
     * Cancel (delete) a label by waybill number.
     *
     * @throws EcontApiException
     * @throws EcontNetworkException
     */
    public function cancelLabel(string $waybillNumber): CancelLabelResult
    {
        $response = $this->adapter->post(
            'Shipments/LabelService.deleteLabel.json',
            ['waybillNumber' => $waybillNumber],
        );

        return CancelLabelResult::fromArray($response);
    }

    /**
     * Track a shipment by waybill number.
     *
     * @throws EcontApiException
     * @throws EcontNetworkException
     */
    public function trackShipment(string $waybillNumber): ShipmentTrackingResult
    {
        $response = $this->adapter->post(
            'Shipments/LabelService.getWaybillContents.json',
            ['waybillNumber' => $waybillNumber],
        );

        return ShipmentTrackingResult::fromArray($response);
    }

    /**
     * Request a courier pick-up.
     *
     * @throws EcontApiException
     * @throws EcontNetworkException
     */
    public function requestCourier(CourierRequest $request): CourierRequestResult
    {
        $response = $this->adapter->post(
            'Shipments/LabelService.requestCourier.json',
            $request->toArray(),
        );

        return CourierRequestResult::fromArray($response);
    }
}
