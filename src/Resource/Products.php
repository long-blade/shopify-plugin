<?php

namespace Shopify\Resource;

use Exception;
use Shopify\Model\Product;
use Shopify\Shopify;
use Shopify\Traits\Exporter;
use Shopify\Traits\HasPagination;

class Products extends Shopify //implements resource interface
{
    use Exporter, HasPagination;

    protected $model = Product::class;

    /**
     * @inheritdoc
     */
    protected $httpTypes = ['POST', 'GET', 'PUT', 'DELETE'];

    /**
     * @param $productId
     *
     * @return mixed
     */
    public function getVariantsForProduct($productId)
    {
        $this->getById($productId);
        $this->addToPathEnd('variants');
        return $this->get()->getResource();
    }

    /**
     * Update an existing product.
     *
     * @param array $data
     * @return mixed
     * @throws Exception
     */
    public function update(array $data)
    {
        if (!isset($data['product']['id'])) {
            throw new Exception(
                sprintf('Missing property \'id\' provided %s.', static::class)
            );
        }
        $this->addToPathEnd($data['product']['id']);
        return $this->put($data);
    }
}
