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
     * Update existing shopify product.
     *
     * @param $id
     * @param array $data
     * @return mixed
     * @throws Exception
     */
    public function updateProduct($id, array $data)
    {
        if (!$id) {
            throw new Exception(
                sprintf('Missing property \'id\' provided %s.', static::class)
            );
        }

        $this->addToPathEnd($id);
        $data['id'] = $id; // Add id to array.
        return $this->put(['product' => $data]);
    }

    /**
     * @param $productId
     * @param array $variantData
     * @return mixed
     * @throws Exception
     */
    public function createNewVariantForProduct($productId, array $variantData)
    {
        if (!$productId) {
            throw new Exception(
                sprintf('Missing property \'productId\' provided %s.', static::class)
            );
        }

        $this->addToPathEnd($productId);
        $this->addToPathEnd('variants');
        return $this->post(['variant' => $variantData]);
    }
}
