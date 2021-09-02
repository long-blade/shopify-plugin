<?php

namespace Shopify\Resource;

use Shopify\Model\Fulfillment;
use Shopify\Shopify;
use Shopify\Traits\HasPagination;

class Orders extends Shopify
{
    use HasPagination;

    /**
     * @inheritdoc
     */
    protected $httpTypes = ['POST', 'GET', 'PUT'];

    /**
     * @inheritdoc
     */
    protected $payload = ['status' => 'any'];

    /**
     * @param $id
     *
     * @return array|null
     */
    public function getOrdersSinceId($id, $status = 'any'): ?array
    {
        $this->payload = ['since_id' => $id, 'limit' => '250', 'fulfillment_status' => $status];
        $this->get($this->payload);

        return $this->getPaginatedResource();
    }

    /**
     * Fulfill an order and all its line items by creating a fulfillment.
     * https://shopify.dev/docs/admin-api/rest/reference/shipping-and-fulfillment/fulfillment#createV2-2021-04
     *
     * @param $id
     * @param Fulfillment $fulfillment
     */
    public function fulfilledOrder($id, Fulfillment $fulfillment)
    {
        $this->addToPathEnd($id); // orders/$id.json
        $this->addToPathEnd('fulfillments'); // orders/$id/fulfillments.json
        return $this->post($fulfillment->getPayload());
    }

    /**
     * Cancel an order
     *
     * POST /admin/api/2021-04/orders/{order_id}/cancel.json
     * https://shopify.dev/docs/admin-api/rest/reference/orders/order#cancel-2021-04
     *
     * @param $id
     * @param array $data
     * @return mixed
     */
    public function canceledOrder($id, array $data = [])
    {
        $this->addToPathEnd($id); // orders/$id.json
        $this->addToPathEnd('cancel'); // orders/$id/cancel.json
        return $this->post($data);
    }
}