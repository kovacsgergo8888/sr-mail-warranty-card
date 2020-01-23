<?php


namespace DataCollector;


use Config\Config;
use ShopRenterApi\Batch\BatchOrderProducts;
use ShopRenterApi\Batch\BatchProductNumberAttributeValues;
use ShopRenterApi\OrderDataApi;

class DataCollector
{
    /**
     * @param $postData
     * @param Config $config
     * @return mixed
     * @throws \Exception
     */
    public function collectData($postData, Config $config)
    {
        $dataFromHook = $postData["orders"]["order"][0];

        $configData = $config->getConfig();

        $orderDataApi = new OrderDataApi($config);

        $dataFromApi = $orderDataApi->getOrderData($dataFromHook["innerId"]);

        $dataFromHook["dateCreated"] = $dataFromApi["dateCreated"];

        unset($dataFromApi);

        $realProductIds = new BatchOrderProducts($config);
        $realProductIds->setOrderProductIds(
            array_map(function ($product) {
                return $product["innerId"];
            }, $dataFromHook["orderProducts"]["orderProduct"]));
        $dataFromHook["realProductIds"] = $realProductIds->getRealProductIdsByOrder();

        $productNumberAttributes = new BatchProductNumberAttributeValues($config);
        $productNumberAttributes->setAttributeId($configData["productWarrantyAttributeId"]);
        $productNumberAttributes->setProductIds(array_values($dataFromHook["realProductIds"]));
        $dataFromHook["warranties"] = $productNumberAttributes->getValuesByProductId();
        $dataFromHook["defaultWarrantyTime"] = $configData["defaultWarrantyTime"];

        return $dataFromHook;
    }
}