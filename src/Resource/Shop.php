<?php

namespace Shopify\Resource;

use Shopify\Shopify;

class Shop extends Shopify
{
    /**
     * @inheritdoc
     */
    protected $httpTypes = ['POST', 'GET'];
}
