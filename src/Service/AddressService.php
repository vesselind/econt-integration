<?php

declare(strict_types=1);

namespace Econt\EcontApi\Service;

use Econt\EcontApi\Collection\CityCollection;
use Econt\EcontApi\Collection\CountryCollection;
use Econt\EcontApi\Collection\QuarterCollection;
use Econt\EcontApi\Collection\StreetCollection;
use Econt\EcontApi\Exception\EcontApiException;
use Econt\EcontApi\Exception\EcontNetworkException;
use Econt\EcontApi\Http\HttpAdapterInterface;
use Econt\EcontApi\Model\Location\Address;
use Econt\EcontApi\Model\Location\City;
use Econt\EcontApi\Model\Location\Country;
use Econt\EcontApi\Model\Location\Quarter;
use Econt\EcontApi\Model\Location\Street;
use Econt\EcontApi\Model\Location\ValidatedAddress;

/**
 * Service for address and nomenclature lookups.
 */
class AddressService
{
    public function __construct(
        private readonly HttpAdapterInterface $adapter,
    ) {
    }

    /**
     * Retrieve all countries available in the Econt system.
     *
     * @throws EcontApiException
     * @throws EcontNetworkException
     */
    public function getCountries(): CountryCollection
    {
        $response = $this->adapter->post(
            'Nomenclatures/NomenclaturesService.getCountries.json',
            ['GetCountriesRequest' => ''],
        );

        $countries = array_map(
            static fn (array $item): Country => Country::fromArray($item),
            (array) ($response['countries'] ?? []),
        );

        return new CountryCollection($countries);
    }

    /**
     * Retrieve cities for a given country code (ISO 3166-1 alpha-3, e.g. "BGR").
     *
     * @throws EcontApiException
     * @throws EcontNetworkException
     */
    public function getCities(string $countryCode): CityCollection
    {
        $response = $this->adapter->post(
            'Nomenclatures/NomenclaturesService.getCities.json',
            ['countryCode' => $countryCode],
        );

        $cities = array_map(
            static fn (array $item): City => City::fromArray($item),
            (array) ($response['cities'] ?? []),
        );

        return new CityCollection($cities);
    }

    /**
     * Retrieve streets for a given city ID.
     *
     * @throws EcontApiException
     * @throws EcontNetworkException
     */
    public function getStreets(int $cityId): StreetCollection
    {
        $response = $this->adapter->post(
            'Nomenclatures/NomenclaturesService.getStreets.json',
            ['cityID' => (string) $cityId],
        );

        $streets = array_map(
            static fn (array $item): Street => Street::fromArray($item),
            (array) ($response['streets'] ?? []),
        );

        return new StreetCollection($streets);
    }

    /**
     * Retrieve quarters (neighbourhoods) for a given city ID.
     *
     * @throws EcontApiException
     * @throws EcontNetworkException
     */
    public function getQuarters(int $cityId): QuarterCollection
    {
        $response = $this->adapter->post(
            'Nomenclatures/NomenclaturesService.getQuarters.json',
            ['cityID' => (string) $cityId],
        );

        $quarters = array_map(
            static fn (array $item): Quarter => Quarter::fromArray($item),
            (array) ($response['quarters'] ?? []),
        );

        return new QuarterCollection($quarters);
    }

    /**
     * Validate an address and return the resolved + validated result.
     *
     * @throws EcontApiException
     * @throws EcontNetworkException
     */
    public function validateAddress(Address $address): ValidatedAddress
    {
        $response = $this->adapter->post(
            'Nomenclatures/NomenclaturesService.validateAddress.json',
            ['address' => $address->toArray()],
        );

        return ValidatedAddress::fromArray($response);
    }
}
