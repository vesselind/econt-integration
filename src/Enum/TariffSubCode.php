<?php

declare(strict_types=1);

namespace Econt\EcontApi\Enum;

/**
 * Econt tariff sub-code backed enum.
 */
enum TariffSubCode: string
{
    case DOOR_DOOR = 'door-door';
    case DOOR_OFFICE = 'door-office';
    case OFFICE_DOOR = 'office-door';
    case OFFICE_OFFICE = 'office-office';
    case DOOR_BANK = 'door-bank';
    case OFFICE_BANK = 'office-bank';
}
