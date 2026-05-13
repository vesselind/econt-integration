<?php

declare(strict_types=1);

namespace Econt\EcontApi\Collection;

use Econt\EcontApi\Model\Location\Quarter;

/**
 * Typed collection of Quarter objects.
 *
 * @extends AbstractCollection<Quarter>
 */
class QuarterCollection extends AbstractCollection
{
    /**
     * @param Quarter[] $items
     */
    public function __construct(array $items = [])
    {
        parent::__construct($items);
    }
}
