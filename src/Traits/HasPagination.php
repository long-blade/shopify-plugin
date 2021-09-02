<?php

namespace Shopify\Traits;

/**
 * TODO: Dispose Response object dependency.
 *
 * Trait HasPagination
 * @package Shopify\Traits
 */
trait HasPagination
{
    /**
     * @var array
     */
    private $data = [];

    /**
     * Get all the resource entities from all pages.
     *
     * @return array|null
     */
    public function getPaginatedResource(): ?array
    {
        // If we dont have any http response do a simple get.
        if (! $this->response)
        {
            $this->get();
        }

        if (isset($this->response->getJson()['errors']))
        {
            echo sprintf('Error request %s.', $this->getResourceAbsolutePath()) . "\n";
            return null;
        }

        // products = [...]
        $this->data[$this->getResourceType()] = $this->doPaginate($this->response, $this->response->getJson()[$this->getResourceType()]);

        return $this->data;
    }

    /**
     * A recursive function to map paginated data.
     *
     * @param $response
     * @param $data
     *
     * @return mixed
     */
    protected function doPaginate($response, $data)
    {
        $steps = $this->paginate($response->getHeaderLine('link'));

        if (! isset($steps['next']))
        {
            return  $data;
        }
        else
        {
            // Get the query data of the next call
            $parts = parse_url($steps['next']);
            parse_str($parts['query'], $query);
            $nextPage = $this->goToTheNextPage($query);
            $newData = array_merge($nextPage->getJson()[$this->getResourceType()], $data);

            return $this->doPaginate($nextPage, $newData);
        }
    }

    /**
     * Create a pagination from a string.
     * https://shopify.dev/tutorials/make-paginated-requests-to-rest-admin-api
     *
     * @param string $string
     *
     * @return mixed
     */
    protected function paginate(string $string): array
    {
        $urls = [];
        $steps = [];

        $regex = '/https?\:\/\/[^\" ]+/i';
        $regex2 = '/previous|next/i';
        preg_match_all($regex, $string, $link);
        preg_match_all($regex2, $string, $step);


        // for the urls
        foreach ($link as $value)
        {
            if (is_array($value))
            {
                foreach ($value as $item)
                {
                    // Url validation
                    if ($url = filter_var($item, FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED))
                    {
                        //Is valid
                        $url = str_replace('>;', '', $url); // TODO: FIX REGEX INSTEAD OF THIS
                        array_push($urls, $url);
                    }
                }
            }
        }

        // For the steps
        foreach ($step as $item)
        {
            if (is_array($item))
            {
                foreach ($item as $i)
                {
                    array_push($steps, $i);
                }
            }
        }

        //Create an array by using one array for keys and another for its values
        return array_combine($steps, $urls);
    }

    /**
     * Get result of next page.
     *
     * @param array $data
     *
     * @return mixed
     */
    protected function goToTheNextPage(array $data)
    {
        return $this->makeHttpRequest($this->url(), 'get', $data);
    }

    /**
     * @inheritDoc
     */
    abstract function getResourceType(): string;
}
