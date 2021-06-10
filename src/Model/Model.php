<?php

namespace Shopify\Model;

use Cake\Collection\Collection;
use Cake\Core\Exception\Exception;
use Shopify\Contracts\ModelInterface;

abstract class Model implements ModelInterface
{
    /**
     * Object properties configuration.
     *
     * An associative array where the keys are the object's property names and
     * the values are their respective default values.
     *
     * When setting a property for which no mutator is set, the new value will
     * be type casted according to the default value.
     *
     * @var array
     */
    protected $properties = [];

    /**
     * Object properties that cannot be set from outside the object.
     *
     * @var array
     */
    protected $private = [];

    /**
     * Object properties that are required to be set for the object to be
     * considered complete.
     *
     * @var array
     */
    protected $required = [];

    /**
     * Object data store.
     *
     * @var mixed Depends on the implementation, array by default.
     */
    protected $data = [];

    /**
     * @var mixed|string
     */
    protected $modelType;

    /**
     * Model constructor.
     *
     * @param $array
     */
    public function __construct($array)
    {
        foreach ($array as $propKey => $propValue) {
            $this->$propKey = $propValue;
        }

        $this->setModelType();
    }


    /**
     * Get object property values.
     *
     * @param $name
     *
     * @return mixed
     *
     * @throws \Exception If property is not available.
     */
    public function __get($name)
    {
        // Skip unknown properties.
        if (! array_key_exists($name, $this->properties))
        {
            throw new Exception(
                sprintf('Undefined property %s::%s.', static::class, $name)
            );
        }

        return $this->properties[$name];
    }

    /**
     * Set object property values.
     *
     * @param string $name
     * @param mixed $value
     *
     * @return mixed
     *
     * @throws \Exception If property is not available.
     */
    public function __set(string $name, $value)
    {
        // Skip unknown properties.
        if (! array_key_exists($name, $this->properties))
        {
            throw new Exception(
                sprintf('Undefined property %s::%s.', static::class, $name)
            );
        }

        // Skip private properties.
        if (in_array($name, $this->private))
        {
            throw new Exception(
                sprintf('Can\'t set inaccessible property %s::%s.', static::class, $name)
            );
        }

        // Assign value to property.
        return $this->data[$name] = $value;
    }

    /**
     * Retrieve the data that are set, or A specified option.
     *
     * @param null $name
     *
     * @return array|mixed
     */
    public function getData($name = null): Collection
    {
        // TODO:maybe throw exception
        if ($name)
        {
            // Skip unknown properties.
            if (! array_key_exists($name, $this->properties))
            {
                throw new Exception(
                    sprintf('Undefined property %s::%s.', static::class, $name)
                );
            }

            return new Collection($this->data[$name]);
        }

        return new Collection($this->data);
    }

    /**
     * Set the models type by class name.
     */
    protected function setModelType(): void
    {
        $namespaceArray = explode('\\', get_class($this));
        $lastPart = end($namespaceArray);
        $this->modelType = strtolower($lastPart);
    }

    /**
     * Check whether the object is incomplete with data.
     *
     * @return bool
     */
    public function isComplete(): bool
    {
        foreach ($this->getRequiredProperties() as $name)
        {
            if (! $this->data[$name] || empty($this->data[$name])) // TODO: Test the empty.. this logic might not work as is
            {
                return false;
            }
        }
        return true;
    }

    /**
     * Get an array of required properties.
     *
     * @return array
     */
    public function getRequiredProperties(): array
    {
        return $this->required;
    }

    /**
     * @return mixed|string
     */
    public function getModelName(): string
    {
        return $this->modelType;
    }

    /**
     * Overwrite for a different struct
     *
     * @inheritdoc
     */
    public function getPayload(): array
    {
        $payload[$this->getModelName()] = $this->getData()->toArray();
        return $payload;
    }
}
