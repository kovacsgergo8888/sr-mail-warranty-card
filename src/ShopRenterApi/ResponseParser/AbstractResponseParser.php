<?php

namespace ShopRenterApi\ResponseParser;

/**
 * ResponseParser
 *
 * @author Kántor András
 * @since 2013.02.22. 14:47
 */
abstract class AbstractResponseParser
{
    /**
     * @var string
     */
    protected $response;

    /**
     * @param string $response
     */
    public function setResponse($response)
    {
        $this->response = $response;
    }

    /**
     * @return array
     */
    abstract public function parse();
}
