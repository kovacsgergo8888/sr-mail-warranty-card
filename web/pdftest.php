<?php

require_once __DIR__ . "/../vendor/autoload.php";

$data = \json_decode(file_get_contents(__DIR__ . "/../test/test.json"), true);

$warranty = new \WarrantyCard\WarrantyCard(new \TCPDF(), $data["orders"]["order"][0]);
$warranty->generateWarrantyCard();
$warranty->getPdf()->Output('asdf', 'I');