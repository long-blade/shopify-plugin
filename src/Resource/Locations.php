<?php

namespace Shopify\Resource;

use Cake\Collection\Collection;
use Shopify\Shopify;

class Locations extends Shopify
{
    /**
     * @inheritdoc
     */
    protected $httpTypes = ['GET'];

    /**
     * Find all trackable Inventory levels for a location.
     *
     * @param $location
     *
     * @return mixed
     */
    public function getInventoryLevelsForLocation($location)
    {
        $this->getById($location); // create the first level path.
        $this->addToPathEnd('inventory_levels');
        $locations = new Collection($this->get(['location_ids' => $location])->getResource('inventory_levels'));

        // Get only trackable locations
        return $locations->filter(function ($item) {
            return $item['available'] != null;
        });
    }
}
