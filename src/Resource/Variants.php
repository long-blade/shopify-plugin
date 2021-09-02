<?php


namespace Shopify\Resource;

use Shopify\Shopify;

//TODO: Implement model for variants. Future update.

class Variants extends Shopify
{
    /**
     * @inheritdoc
     */
    protected $httpTypes = ['PUT'];

    public function updateVariant($id, array $data)
    {
        $this->addToPathEnd($id);
        $data['id'] = $id; // Add id to array.
        return $this->put(['variant' => $data]);
    }
}