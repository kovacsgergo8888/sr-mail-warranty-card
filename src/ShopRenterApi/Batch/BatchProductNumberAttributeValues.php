<?php
/**
 * Created by PhpStorm.
 * User: kgergo
 * Date: 2019.03.07.
 * Time: 18:42
 */

namespace ShopRenterApi\Batch;

//numberAttributeValue-attribute_id=9&product_id=90

use Config\Config;
use ShopRenterApi\ApiCall;

class BatchProductNumberAttributeValues extends AbstractBatch
{
    protected $productIds;

    protected $attributeId;

    /**
     * @param mixed $productIds
     */
    public function setProductIds($productIds)
    {
        $this->productIds = $productIds;
    }

    /**
     * @param mixed $attributeId
     */
    public function setAttributeId($attributeId)
    {
        $this->attributeId = $attributeId;
    }


    public function getUri($resourceId)
    {
        return \base64_encode("numberAttributeValue-attribute_id={$this->attributeId}&product_id=$resourceId");
    }

    public function buildRequests()
    {
        foreach ($this->productIds as $productId) {
            $this->requests[] = [
                "method" => "GET",
                "uri" => "{$this->srApiUrl}/numberAttributeValues/{$this->getUri($productId)}",
            ];
        }
    }

    /**
     * @return array
     */
    public function getValuesByProductId()
    {
        $this->executeBatchRequest();
        $responseBodies = array_map(function ($response) {
            $body = $response["response"]["body"];
            return [
                "productHref" => $body["product"]["href"],
                "value" => $body["value"],
            ];
        }, $this->response["requests"]["request"]);

        $return = [];
        foreach ($responseBodies as $item) {
            $productId = $this->getProductIdByHref($item["productHref"]);
            $return[$productId] = $item["value"];
        }
        return $return;
    }

}