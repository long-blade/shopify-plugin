<?php

namespace Shopify\Contracts;

use Cake\Collection\Collection;

interface ModelInterface
{
    /**
     * Return the payload data structured as it should be
     *
     * @return array
     */
    public function getPayload(): array;

    /**
     * @return string
     */
    public function getModelName(): string;

    /**
     * @return array
     */
    public function getRequiredProperties(): array;

    /**
     * @return bool
     */
    public function isComplete(): bool;

    /**
     * @param null $name
     *
     * @return mixed
     */
    public function getData($name = null): Collection;

}
