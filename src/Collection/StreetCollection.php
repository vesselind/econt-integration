<?php

declare(strict_types=1);

namespace Econt\EcontApi\Collection;

use Econt\EcontApi\Model\Location\Street;

/**
 * Typed collection of Street objects.
 *
 * @extends AbstractCollection<Street>
 */
class StreetCollection extends AbstractCollection
{
    /**
     * @param Street[] $items
     */
    public function __construct(array $items = [])
    {
        parent::__construct($items);
    }
}
