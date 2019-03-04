<?php
/**
 * Created by PhpStorm.
 * User: kgergo
 * Date: 2019.03.03.
 * Time: 20:35
 */

namespace ShopRenterApi;


use Config\Config;

class OrderDataApi
{

    protected $srApiUrl;

    protected $apiCall;

    /**
     * OrderDataApi constructor.
     * @param ApiCall $apiCall
     */
    public function __construct(Config $config)
    {
        $configData = $config->getConfig();
        $this->srApiUrl = $configData["srApiUrl"];
        $this->apiCall = new ApiCall($configData["apiUsername"], $configData["apiPassword"]);
    }

    public function getQuery($orderId)
    {
        return base64_encode("order-order_id=$orderId");
    }

    /**
     * @param $orderId
     * @return array
     * @throws \Exception
     */
    public function getOrderData($orderId)
    {
        try {
            $this->apiCall->execute("GET", "{$this->srApiUrl}/orders/{$this->getQuery($orderId)}");
            return $this->apiCall->getResponse()->getParsedResponseBody();
        } catch (\Exception $e) {
            throw new \Exception("Something went wrong!");
        }
    }



}