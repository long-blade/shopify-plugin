<?php

namespace Shopify\Resource;

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
}
