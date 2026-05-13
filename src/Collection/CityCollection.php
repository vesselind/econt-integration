<?php

declare(strict_types=1);

namespace Econt\EcontApi\Collection;

use Econt\EcontApi\Model\Location\City;

/**
 * Typed collection of City objects.
 *
 * @extends AbstractCollection<City>
 */
class CityCollection extends AbstractCollection
{
    /**
     * @param City[] $items
     */
    public function __construct(array $items = [])
    {
        parent::__construct($items);
    }
}
