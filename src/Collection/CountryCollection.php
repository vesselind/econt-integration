<?php

declare(strict_types=1);

namespace Econt\EcontApi\Collection;

use Econt\EcontApi\Model\Location\Country;

/**
 * Typed collection of Country objects.
 *
 * @extends AbstractCollection<Country>
 */
class CountryCollection extends AbstractCollection
{
    /**
     * @param Country[] $items
     */
    public function __construct(array $items = [])
    {
        parent::__construct($items);
    }
}
