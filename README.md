# Shopify plugin for CakePHP
A shopify plugin that interacts with [shopify api](https://shopify.dev/docs/admin-api/rest/reference)
## Installation

You can install this plugin into your CakePHP application using [composer](https://getcomposer.org).

The recommended way to install composer packages is:

```
composer require mmavroforakis/shopify-plugin
```
## Site object

In order to interact with the plugin you need to create a site object with the minimum require data.
```php
use Shopify\Model\Site;

$siteId = 0; // a unique id (it can be the id of the entry in a database table)
$site = new Site('hostname', 'apiKey', 'apiPassword', $siteId);
```

This object is injected to the constructor on each resource class.

```php
use Shopify\Model\Site;
$site = new Site('hostname', 'apiKey', 'apiPassword', 0);

// Then use this object for any resource, for example:
use Shopify\Resource\Products;
$products = new Products($site);
```

##Resources

###Shop
The resource lets you retrieve information about the store but doesn't let you update any information.
```php
use Shopify\Model\Site;
$site = new Site('hostname', 'apiKey', 'apiPassword', 0);

use Shopify\Resource\Shop;
$shop = new Shop($site);

// Then we perform a simple get request by chaining the getResource method
$shopInfo = $shop->getResource();
```
Response will be an array. [Shop properties](https://shopify.dev/docs/admin-api/rest/reference/store-properties/shop#properties-2021-01)
___
###Product
The [Product](https://shopify.dev/docs/admin-api/rest/reference/products/product#properties-2021-01) resource lets you update and create products in a merchant's store.

```php
use Shopify\Resource\Products;
$productsResource = new Products($site);
```

How to fetch info about products:
```php
//Retrieves a list of products
$products = $productsResource->getResource();

//Retrieves a single product
$product = $productsResource->getById(0102040506);

//Retrieves a list of product variants
$product = $productsResource->getVariantsForProduct(0102040506);
```

How to create a new Product:
```php
$productData = [
    'title' => 'This is a product title',
    'description' => 'This is a product description'
];

$product = new \Shopify\Model\Product($productData);

//Creates a new product by passing the product object created.
$productsResource->post($product);
```

You can also export to json file the response of a product query like so:
```php
// $productsResource = new Products($site);
$productsResource->export(['products' => [...]]);
```
This will export the response object to a json file in a location specified in the `Shopify/config/bootstrap.php` file:
```php
[
'export' => [
        'path' => RESOURCES . 'json' . DS,
        'file_type' => 'json',
        'lifetime' => 60 * 60 * 3, // The lifetime which a file is consider to be old
    ],
];
```

___

###Inventory
An [inventory level](https://shopify.dev/docs/admin-api/rest/reference/inventory/inventorylevel#properties-2021-01) represents the available quantity of an inventory item at a specific location.

How to Set availability for a product:
```php
$inventory = [
    'location_id' => 987654321, // The ID of the location that the inventory level belongs to.
    'inventory_item_id' => 123456789, // The ID of the inventory item that the inventory level belongs to.
    'available' => 10 // The quantity of inventory items available for sale.
];
$inventory = new \Shopify\Model\Inventory($inventory); // Create the entity;
$inventoryResource = new \Shopify\Resource\InventoryLevels($site);
$inventoryResource->setInventory($inventory);
```

How to Adjust the available quantity of an inventory item by 5 at a single location:
```php
$inventory = [
    'location_id' => 987654321, // The ID of the location that the inventory level belongs to.
    'inventory_item_id' => 123456789, // The ID of the inventory item that the inventory level belongs to.
    'available_adjustment' => 5 // The amount to adjust the available inventory quantity. Send negative values to subtract from the current available quantity.
];
$inventory = new \Shopify\Model\Inventory($inventory); // Create the entity;
$inventoryResource = new \Shopify\Resource\InventoryLevels($site);
$inventoryResource->adjustInventory($inventory);
```
____

## License
[MIT](https://choosealicense.com/licenses/mit/)
