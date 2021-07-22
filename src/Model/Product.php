<?php

namespace Shopify\Model;

/**
 * Create a new Product shopify obj.
 *
 * Class Product
 * @package Shopify\Model
 */
class Product extends Model
{
    protected $properties = [
        'id' => 0, // TODO: May be this is not needed for a new complete product
        'title' => '',
        'body_html' => '',
        'vendor' => '',
        'product_type' => '',
        'created_at' => '',
        'handle' => '',
        'updated_at' => '',
        'published_at' => '',
        'template_suffix' => null,
        "status" => 'active',
        'published_scope' => '',
        'tags' => '',
        'admin_graphql_api_id' => '',
        'variants' => [],
        'options' => [],
        'images' => [],
        'image' => [],
    ];

    protected $required = [
      'title', // Creating a product without a title will return an error
    ];

    public function getVariants()
    {
        return $this->getData('variants');
    }
}
