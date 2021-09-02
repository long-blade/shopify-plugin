<?php

namespace Shopify\Model;

/**
 * Class Inventory
 *
 * @property integer $location_id The ID of the inventory item.
 * @property integer $inventory_item_id The ID of the location that the inventory level belongs to.
 * @property integer $available The quantity of inventory items available for sale
 * @property integer $available_adjustment The amount to adjust the available inventory quantity.
 *  Send negative values to subtract from the current available quantity.
 *
 * @package Shopify\Model
 */
class Inventory extends Model
{
    protected $properties = [
        'location_id' => 0,
        'inventory_item_id' => 0,
        'available' => 0,
        'available_adjustment' => 0,
    ];

    protected $required = [
        'location_id',
        'inventory_item_id',
    ];

    /**
     * @inheritdoc
     */
    public function getPayload(): array
    {
        return $payload = $this->getData()->toArray();
    }
}
