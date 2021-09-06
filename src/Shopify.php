<?php

namespace Shopify;

use Cake\Core\Configure;
use Exception;
use Shopify\Model\Site;

/**
 * Class Shopify
 *
 * @package Shopify
 */
abstract class Shopify extends Request
{
    /**
     * @var string
     */
    protected $version;

    /**
     * @var string
     */
    protected $basePath;

    /**
     * @var \Shopify\Model\Site
     */
    protected $site;

    /**
     * The target ID. (ex. product_id, order_id)
     * @var
     */
    protected $resourceId;

    /**
     * @var \Cake\Http\Client\Response|null
     */
    protected $response;

    /**
     * @var string
     */
    protected $resourceType;

    /**
     * @var string
     */
    protected $apiEndpoint;

    /**
     * The path array parts.
     *
     * @var
     */
    protected $path = [];

    /**
     * Shopify constructor.
     *
     * @param \Shopify\Model\Site $site
     */
    public function __construct(Site $site)
    {
        parent::__construct();
        $this->version = (string)Configure::read('shopify_api.version');
        $this->basePath = (string)Configure::read('shopify_api.admin_path');
        $this->site = $site;
        $this->setResource(); //$resourceType
        $this->resetPath();
    }

    /**
     * Construct url for the call.
     *
     * @param string $resource
     *
     * @return string
     */
    protected function url(): string
    {
        $apiKey = $this->site->getApiKey();
        $password = $this->site->getApiPassword();
        $hostname = $this->site->getHostname();

        return "https://{$apiKey}:{$password}@{$hostname}{$this->endpoint()}";
    }

    /**
     * Construct the final request endpoint.
     *
     * @return string
     */
    protected function endpoint(): string
    {
        $this->apiEndpoint = "{$this->basePath}{$this->version}/{$this->getResourcePath()}.json";
        $this->resetPath(); // after construction of path reset it to avoid previous call endpoints.
        return $this->apiEndpoint;
    }

    /**
     * Resetting path the initial state.
     */
    protected function resetPath()
    {
        $this->path = [];
        array_push($this->path, $this->resourceType);
    }

    /**
     * Append to array a path
     *
     * @param string $part
     */
    protected function addToPathEnd(string $part)
    {
        array_push($this->path, $part);
    }

    /**
     * Construct a resource path with the type of resource.
     *
     * @return string
     */
    protected function getResourcePath(): string
    {
        if (count($this->path) == 1) {
            return reset($this->path);
        }
        return implode(DS, $this->path);
    }

    /**
     * Getter For Resource type.
     *
     * @return string
     */
    protected function getResourceType(): string
    {
        return $this->resourceType;
    }

    /**
     * @param null $key
     *
     * @return array|null
     * @throws Exception
     */
    public function getResource($key = null): ?array
    {
        // If we don't have any http response do a simple get.
        if (!$this->response) {
            $this->get();
        }

        if (isset($this->response->getJson()['errors'])) {
            throw new Exception(
                sprintf('Error on %s::getResource() method. %s', static::class, json_encode($this->response->getJson()['errors']))
            );
        }

        return isset($key) ? $this->response->getJson()[$key] : $this->response->getJson();
    }

    /**
     * Create a resource type based on class name for current instance.
     */
    private function setResource()
    {
        $namespaceArray = explode('\\', get_class($this));
        $lastPart = end($namespaceArray);
        // CamelCase to snake_case
        $lastPart = ltrim(strtolower(preg_replace('/[A-Z]([A-Z](?![a-z]))*/', '_$0', $lastPart)), '_');
        $this->resourceType = strtolower($lastPart);
    }
}
