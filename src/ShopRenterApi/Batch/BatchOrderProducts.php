<?php
/**
 * Created by PhpStorm.
 * User: kgergo
 * Date: 2019.03.09.
 * Time: 15:40
 */

namespace ShopRenterApi\Batch;


class BatchOrderProducts extends AbstractBatch
{
    protected $orderProductIds;

    /**
     * @return mixed
     */
    public function getOrderProductIds()
    {
        return $this->orderProductIds;
    }

    /**
     * @param mixed $orderProductIds
     */
    public function setOrderProductIds($orderProductIds)
    {
        $this->orderProductIds = $orderProductIds;
    }


    public function getUri($resourceId)
    {
        return \base64_encode("orderProduct-order_product_id=$resourceId");
    }

    public function buildRequests()
    {
        foreach ($this->orderProductIds as $orderProductId) {
            $this->requests[] = [
                "method" => "GET",
                "uri" => "{$this->srApiUrl}/orderProducts/{$this->getUri($orderProductId)}",
            ];
        }
    }

    public function getRealProductIdsByOrder()
    {
        $this->executeBatchRequest();
        $return = [];
        foreach ($this->response["requests"]["request"] as $response) {
            $body = $response["response"]["body"];
            $orderProductId = $this->getProductIdByHref($body["id"]);
            $productId = $this->getProductIdByHref($body["product"]["href"]);
            $return[$productId] = $orderProductId;

        }

        return $return;
    }
}