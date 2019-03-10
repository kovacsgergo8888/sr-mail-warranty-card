<?php

require_once __DIR__ . "/../vendor/autoload.php";

use Config\Config;
use ShopRenterApi\Batch\BatchOrderProducts;
use ShopRenterApi\Batch\BatchProductNumberAttributeValues;
use ShopRenterApi\OrderDataApi;
use WarrantyCard\WarrantyCard;

$data = \json_decode(file_get_contents(__DIR__ . "/../test/test.json"), true);
$dataFromHook = $data["orders"]["order"][0];

$config = new Config();
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

$warranty = new WarrantyCard(new \TCPDF(), $dataFromHook);
$warranty->generateWarrantyCard();
$warranty->getPdf()->Output('asdf', 'I');