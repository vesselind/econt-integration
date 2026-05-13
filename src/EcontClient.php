<?php

declare(strict_types=1);

namespace Econt\EcontApi;

use Econt\EcontApi\Http\HttpAdapterInterface;
use Econt\EcontApi\Service\AddressService;
use Econt\EcontApi\Service\OfficeService;
use Econt\EcontApi\Service\ShipmentService;
use Psr\Log\LoggerInterface;

/**
 * Main entry point for the Econt API library.
 *
 * Usage:
 *   $config = new EcontConfiguration('username', 'password');
 *   $adapter = new SymfonyHttpAdapter($httpClient, $config);
 *   $client  = new EcontClient($config, $adapter);
 *
 *   $countries = $client->address()->getCountries();
 */
class EcontClient
{
    private ?AddressService $addressService = null;
    private ?OfficeService $officeService = null;
    private ?ShipmentService $shipmentService = null;

    public function __construct(
        private readonly EcontConfiguration $configuration,
        private readonly HttpAdapterInterface $adapter,
        private readonly ?LoggerInterface $logger = null,
    ) {
    }

    /**
     * Get the Address service for country/city/street/quarter lookups and validation.
     */
    public function address(): AddressService
    {
        if ($this->addressService === null) {
            $this->addressService = new AddressService($this->adapter);
        }

        return $this->addressService;
    }

    /**
     * Get the Office service for office lookups.
     */
    public function office(): OfficeService
    {
        if ($this->officeService === null) {
            $this->officeService = new OfficeService($this->adapter);
        }

        return $this->officeService;
    }

    /**
     * Get the Shipment service for label creation, pricing, tracking, and courier requests.
     */
    public function shipment(): ShipmentService
    {
        if ($this->shipmentService === null) {
            $this->shipmentService = new ShipmentService($this->adapter);
        }

        return $this->shipmentService;
    }

    /**
     * Access the underlying configuration.
     */
    public function getConfiguration(): EcontConfiguration
    {
        return $this->configuration;
    }

    /**
     * Access the underlying HTTP adapter.
     */
    public function getAdapter(): HttpAdapterInterface
    {
        return $this->adapter;
    }

    /**
     * Access the logger if one was provided.
     */
    public function getLogger(): ?LoggerInterface
    {
        return $this->logger;
    }
}
