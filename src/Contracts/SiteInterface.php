<?php

namespace Shopify\Contracts;

interface SiteInterface
{
    /**
     * A unique id for this entiry (perhaps the id of the table if any).
     * This is used to construct a unique json file name on the export
     *
     * (exp. resources/json/order_{EntryId}.json)
     * @return int
     */
    public function getEntryId(): int;

    /**
     * The api password for shopify site.
     *
     * @return string
     */
    public function getApiPassword(): string;

    /**
     * The api key for shopify site.
     *
     * @return string
     */
    public function getApiKey(): string;

    /**
     * The hostname for shopify site.
     *
     * @return string
     */
    public function getHostname(): string;
}
