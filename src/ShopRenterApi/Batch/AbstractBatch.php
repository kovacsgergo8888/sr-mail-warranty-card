<?php
/**
 * Created by PhpStorm.
 * User: kgergo
 * Date: 2019.03.09.
 * Time: 15:41
 */

namespace ShopRenterApi\Batch;


use Config\Config;
use ShopRenterApi\ApiCall;

abstract class AbstractBatch
{
    protected $srApiUrl;
    /**
     * @var ApiCall
     */
    protected $apiCall;
    protected $requests;
    /**
     * @var Config
     */
    protected $config;
    /**
     * @var []
     */
    protected $response;

    public function __construct(Config $config)
    {
        $configData = $config->getConfig();
        $this->srApiUrl = $configData["srApiUrl"];
        $this->apiCall = new ApiCall($configData["apiUsername"], $configData["apiPassword"]);
    }

    public function executeBatchRequest()
    {
        try {
            $this->buildRequests();
            $this->apiCall->execute("POST", $this->srApiUrl . "/batch", ["requests" => $this->requests]);
            $this->response = $this->apiCall->getResponse()->getParsedResponseBody();
        } catch (\Exception $e) {
            echo "bad happened: " . $e->getMessage();
            return [];
        }
    }

    abstract public function getUri($resourceId);
    abstract public function buildRequests();

    public function getProductIdByHref($productHref)
    {
        return array_pop(explode("=", \base64_decode(array_pop(explode("/", $productHref)))));
    }
}