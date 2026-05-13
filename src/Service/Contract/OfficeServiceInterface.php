<?php

declare(strict_types=1);

namespace Econt\EcontApi\Service\Contract;

use Econt\EcontApi\Model\Office\Office;
use Econt\EcontApi\Model\Response\EcontResponse;

interface OfficeServiceInterface
{
    public function requestOffices(): EcontResponse;

    public function requestOfficesExDay(string $cityName, ?string $countryCode = null): EcontResponse;

    public function requestOffice(string $officeCode): EcontResponse;

    public function getOffices(?string $cityName = null, ?string $countryCode = null): array;

    public function getOffice(string $officeCode): ?Office;
}