<?php

declare(strict_types=1);

namespace Econt\EcontApi\Enum;

/**
 * Econt shipment type backed enum.
 */
enum ShipmentType: string
{
    case DOCUMENT = 'document';
    case PACK = 'pack';
    case POST_PACK = 'post_pack';
    case PALLET = 'pallet';
    case CARGO = 'cargo';
    case DOCUMENTPALLET = 'documentpallet';
    case BIG_LETTER = 'big_letter';
    case SMALL_LETTER = 'small_letter';
    case MONEY_TRANSFER = 'money_transfer';
}
