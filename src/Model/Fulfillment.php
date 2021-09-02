<?php


namespace Shopify\Model;

/**
 * https://shopify.dev/docs/admin-api/rest/reference/shipping-and-fulfillment/fulfillment#create-2021-04
 *
 * Class Fulfillment
 * @package Shopify\Model
 */
class Fulfillment extends Model
{
    protected $properties = [
        'location_id' => '',
        'tracking_number' => '',
        'tracking_url' => '',
        'tracking_company' => '',
        'notify_customer' => true,
        'amount' => '0',
    ];

    protected $required = [
        'location_id', // Fulfill without a location_id results in a bad request
    ];
}