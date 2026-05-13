<?php

declare(strict_types=1);

namespace Econt\EcontApi\Service;

use Econt\EcontApi\Client\EcontClientInterface;
use Econt\EcontApi\Model\Address\City;
use Econt\EcontApi\Model\Address\Country;
use Econt\EcontApi\Model\Address\Quarter;
use Econt\EcontApi\Model\Address\Street;
use Econt\EcontApi\Model\Response\EcontResponse;
use Econt\EcontApi\Service\Contract\AddressServiceInterface;

class AddressService implements AddressServiceInterface
{
    private const SERVICE_COUNTRIES = 'Services.ExchangeRates.requestCountries';
    private const SERVICE_CITIES = 'Services.ExchangeRates.requestCities';
    private const SERVICE_STREETS = 'Services.ExchangeRates.requestStreets';
    private const SERVICE_QUARTERS = 'Services.ExchangeRates.requestQuarters';

    private EcontClientInterface $client;

    public function __construct(EcontClientInterface $client)
    {
        $this->client = $client;
    }

    public function requestCountries(?string $countryCode = null): EcontResponse
    {
        $data = [];

        if ($countryCode !== null) {
            $data['country_code'] = $countryCode;
        }

        return $this->client->request(self::SERVICE_COUNTRIES, $data);
    }

    public function requestCities(string $countryCode, ?string $cityName = null, ?bool $onlyWithOffice = null): EcontResponse
    {
        $data = [
            'country_code' => $countryCode,
        ];

        if ($cityName !== null) {
            $data['name'] = $cityName;
        }

        if ($onlyWithOffice !== null) {
            $data['only_with_office'] = $onlyWithOffice ? 'true' : 'false';
        }

        return $this->client->request(self::SERVICE_CITIES, $data);
    }

    public function requestStreets(string $cityId, ?string $streetName = null): EcontResponse
    {
        $data = [
            'city_id' => $cityId,
        ];

        if ($streetName !== null) {
            $data['name'] = $streetName;
        }

        return $this->client->request(self::SERVICE_STREETS, $data);
    }

    public function requestQuarters(string $cityId, ?string $quarterName = null): EcontResponse
    {
        $data = [
            'city_id' => $cityId,
        ];

        if ($quarterName !== null) {
            $data['name'] = $quarterName;
        }

        return $this->client->request(self::SERVICE_QUARTERS, $data);
    }

    public function getCountries(?string $countryCode = null): array
    {
        $response = $this->requestCountries($countryCode);

        if (!$response->isSuccess()) {
            return [];
        }

        $data = $response->getData();
        if (!isset($data['countries']) || !is_array($data['countries'])) {
            return [];
        }

        return array_map(
            fn($item) => Country::fromArray($item),
            $data['countries']
        );
    }

    public function getCities(string $countryCode, ?string $cityName = null, ?bool $onlyWithOffice = null): array
    {
        $response = $this->requestCities($countryCode, $cityName, $onlyWithOffice);

        if (!$response->isSuccess()) {
            return [];
        }

        $data = $response->getData();
        if (!isset($data['cities']) || !is_array($data['cities'])) {
            return [];
        }

        return array_map(
            fn($item) => City::fromArray($item),
            $data['cities']
        );
    }

    public function getStreets(string $cityId, ?string $streetName = null): array
    {
        $response = $this->requestStreets($cityId, $streetName);

        if (!$response->isSuccess()) {
            return [];
        }

        $data = $response->getData();
        if (!isset($data['streets']) || !is_array($data['streets'])) {
            return [];
        }

        return array_map(
            fn($item) => Street::fromArray($item),
            $data['streets']
        );
    }

    public function getQuarters(string $cityId, ?string $quarterName = null): array
    {
        $response = $this->requestQuarters($cityId, $quarterName);

        if (!$response->isSuccess()) {
            return [];
        }

        $data = $response->getData();
        if (!isset($data['quarters']) || !is_array($data['quarters'])) {
            return [];
        }

        return array_map(
            fn($item) => Quarter::fromArray($item),
            $data['quarters']
        );
    }

    public function getCountry(string $countryCode): ?Country
    {
        $countries = $this->getCountries($countryCode);

        if (count($countries) === 0) {
            return null;
        }

        return $countries[0];
    }

    public function getCity(string $countryCode, string $cityName): ?City
    {
        $cities = $this->getCities($countryCode, $cityName);

        if (count($cities) === 0) {
            return null;
        }

        return $cities[0];
    }
}