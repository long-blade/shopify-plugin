<?php


namespace Shopify\Resource;

use Exception;
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
        if (!$id) {
            throw new Exception(
                sprintf('Missing property \'id\' provided %s.', static::class)
            );
        }

        $this->addToPathEnd($id);
        $data['id'] = $id; // Add id to array.
        return $this->put(['variant' => $data]);
    }
}