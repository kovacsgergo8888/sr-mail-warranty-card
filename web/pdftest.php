<?php

require_once __DIR__ . "/../vendor/autoload.php";

use Config\Config;
use ShopRenterApi\OrderDataApi;

$data = \json_decode(file_get_contents(__DIR__ . "/../test/test.json"), true);
$dataFromHook = $data["orders"]["order"][0];

$orderDataApi = new OrderDataApi(new Config());

$dataFromApi = $orderDataApi->getOrderData($dataFromHook["innerId"]);

$dataFromHook["dateCreated"] = $dataFromApi["dateCreated"];

unset($dataFromApi);

$warranty = new \WarrantyCard\WarrantyCard(new \TCPDF(), $dataFromHook);
$warranty->generateWarrantyCard();
$warranty->getPdf()->Output('asdf', 'I');