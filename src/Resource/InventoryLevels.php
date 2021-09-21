<?php

namespace Shopify\Resource;

use Shopify\Model\Inventory;
use Shopify\Shopify;

class InventoryLevels extends Shopify
{
    protected $model = Inventory::class;
    /**
     * @inheritdoc
     */
    protected $httpTypes = ['POST', 'GET', 'DELETE'];

    /**
     * Adjust the inventory for an inventory item.
     *
     * @param Inventory $inventoryItem
     *
     * @return $this
     */
    public function adjustInventory(Inventory $inventoryItem): InventoryLevels
    {
        $this->addToPathEnd('adjust');
        $this->post($inventoryItem)->getResource();

        return $this;
    }

    /**
     * Set the inventory for an inventory item.
     *
     * @param Inventory $inventoryItem
     *
     * @return $this
     */
    public function setInventory(Inventory $inventoryItem): InventoryLevels
    {
        $this->addToPathEnd('set');
        $this->post($inventoryItem)->getResource();
        return $this;
    }
}
