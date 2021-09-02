<?php

namespace Shopify\Model;

use Shopify\Contracts\SiteInterface;

/**
 * Class Site
 * This represents a site object with the basic properties needed
 * in order to connect to a Shopify site.
 *
 * This needs to be dynamic in order to support multiple calls to
 * a different sites in one application
 *
 * @package Shopify\Model
 */
class Site implements SiteInterface
{
    /**
     * @var string
     */
    private $hostname;

    /**
     * @var string
     */
    private $apiKey;

    /**
     * @var string
     */
    private $apiPassword;

    /**
     * @var int|mixed
     */
    private $entryId;

    /**
     * Site constructor.
     *
     * @param string $hostname
     * @param string $apiKey
     * @param string $apiPassword
     * @param int $entryId
     */
    public function __construct(string $hostname, string $apiKey, string $apiPassword, $entryId = 0)
    {
        $this->hostname = $hostname;
        $this->apiKey = $apiKey;
        $this->apiPassword = $apiPassword;
        $this->entryId = $entryId;
    }

    /**
     * @inheritdoc
     */
    public function getEntryId(): int
    {
        return $this->entryId;
    }

    /**
     * @inheritdoc
     */
    public function getApiPassword(): string
    {
        return $this->apiPassword;
    }

    /**
     * @inheritdoc
     */
    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    /**
     * @inheritdoc
     */
    public function getHostname(): string
    {
        return $this->hostname;
    }
}
