<?php

namespace Shopify\Resource;

use Shopify\Shopify;
use Shopify\Traits\HasPagination;

class Orders extends Shopify
{
    use HasPagination;

    /**
     * @inheritdoc
     */
    protected $httpTypes = ['GET'];

    /**
     * @inheritdoc
     */
    protected $payload = ['status' => 'any'];

    /**
     * @param $id
     *
     * @return array|null
     */
    public function getOrdersSinceId($id): ?array
    {
        $this->payload = ['since_id' => $id, 'limit' => '250'];
        $this->get($this->payload);

        return $this->getPaginatedResource();
    }
}