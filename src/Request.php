<?php

namespace Shopify;

use Cake\Core\Exception\Exception;
use Cake\Http\Client;
use Shopify\Model\Model;

/**
 * Class Request
 * @method post($array = [])
 * @method get($array = [])
 * @method put($array = [])
 * @method delete($array = [])
 * @package Shopify
 */
abstract class Request
{
    /**
     * @var \Cake\Http\Client
     */
    private $client;

    /**
     * @var array
     */
    protected $headers = [];

    /**
     * Available http methods for requests
     *
     * @var string[]
     */
    protected $httpTypes = ['post', 'get', 'put', 'delete'];

    /**
     * The payload for the request
     * @var
     */
    protected $payload;

    /**
     * The associate resource model.
     *
     * For products the assoc model is product.
     *
     * @var null
     */
    protected $model = null;

    /**
     * Request constructor.
     */
    public function __construct()
    {
        $this->client = new Client();
    }

    /**
     * @param $endpoint
     * @param string $method
     * @param array $payload
     * @param array $headers
     *
     * @return mixed
     */
    protected function makeHttpRequest(string $endpoint, string $method = 'get', $payload = [], array $headers = [])
    {
        return $this->client->$method($endpoint, $payload, $headers);
    }

    /**
     * @param $method
     * @param $arguments
     *
     * @return $this
     */
    public function __call($method, $arguments): Request
    {
        if (!empty($arguments)) {
            if ($arguments[0] instanceof Model) {
                if ($arguments[0]->isComplete()) {
                    $this->payload = $arguments[0]->getPayload();
                } else {
                    throw new Exception(
                        sprintf('Incomplete object::%s provided %s::%s.', $arguments[0]->getModelName(), static::class, $method)
                    );
                }
            } elseif (is_array($arguments[0]) && !empty($arguments[0])) {
                $this->payload = $arguments[0];
            }
        }

        // Convert array values to lowercase
        $http = array_map('strtolower', $this->httpTypes);

        // Invoke for methods
        if (in_array($method, $http)) //TODO: Throw exception for unauthorized method types now we just ignore themslac
        {
            $this->response = $this->makeHttpRequest($this->url(), $method, $this->payload, $this->headers);
        } else {
            throw new Exception(
                sprintf('Undefined method %s::%s.', static::class, $method)
            );
        }

        return $this;
    }

    /**
     * Get a single resource by id.
     *
     * @param $id
     *
     * @return mixed
     */
    public function getById($id)
    {
        $this->resourceId = $id;
        array_push($this->path, $this->resourceId);
        $this->response = $this->makeHttpRequest($this->url());
        $res = (array)$this->getResource();
        $res = reset($res);

        if ($this->model) {
            return new $this->model($res);
        }

        return $res;
    }

    /**
     * @return mixed|null
     */
    public function getErrors(): ?array
    {
        $errors = $this->response->getJson();
        if (empty($errors) || !isset($errors['errors']) || empty($errors['errors'])) {
            return null;
        }

        return $errors['errors'];
    }
}
