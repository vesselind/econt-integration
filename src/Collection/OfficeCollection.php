<?php

declare(strict_types=1);

namespace Econt\EcontApi\Collection;

use Econt\EcontApi\Model\Office\Office;

/**
 * Typed collection of Office objects.
 *
 * @extends AbstractCollection<Office>
 */
class OfficeCollection extends AbstractCollection
{
    /**
     * @param Office[] $items
     */
    public function __construct(array $items = [])
    {
        parent::__construct($items);
    }
}
