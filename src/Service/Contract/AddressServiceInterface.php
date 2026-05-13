<?php

declare(strict_types=1);

namespace Econt\EcontApi\Service\Contract;

use Econt\EcontApi\Model\Address\City;
use Econt\EcontApi\Model\Address\Country;
use Econt\EcontApi\Model\Address\Quarter;
use Econt\EcontApi\Model\Address\Street;
use Econt\EcontApi\Model\Response\EcontResponse;

interface AddressServiceInterface
{
    public function requestCountries(?string $countryCode = null): EcontResponse;

    public function requestCities(string $countryCode, ?string $cityName = null, ?bool $onlyWithOffice = null): EcontResponse;

    public function requestStreets(string $cityId, ?string $streetName = null): EcontResponse;

    public function requestQuarters(string $cityId, ?string $quarterName = null): EcontResponse;

    public function getCountries(?string $countryCode = null): array;

    public function getCities(string $countryCode, ?string $cityName = null, ?bool $onlyWithOffice = null): array;

    public function getStreets(string $cityId, ?string $streetName = null): array;

    public function getQuarters(string $cityId, ?string $quarterName = null): array;

    public function getCountry(string $countryCode): ?Country;

    public function getCity(string $countryCode, string $cityName): ?City;
}