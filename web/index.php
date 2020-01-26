<?php

require_once __DIR__ . "/../vendor/autoload.php";

use Config\Config;
use DataCollector\DataCollector;
use EmailBuilder\EmailBuilderProvider;

error_reporting(E_ERROR);

if (empty($_POST["data"])) {
    exit("no data");
}
try {
    $data = \json_decode($_POST["data"], true);
    $config = new Config();

    $dataCollector = new DataCollector();
    $dataFromHook = $dataCollector->collectData($data, $config);

    $mailProvider = new EmailBuilderProvider($config, $dataFromHook);
    $mailBuilder = $mailProvider->getBuilder();
    $mail = $mailBuilder->buildEmail();
    file_put_contents('inputData.log', json_encode($dataFromHook) . "\n", FILE_APPEND);
    $mail->send();
} catch (\Exception $exception) {
    file_put_contents(__DIR__ . "/../log/" . date("Y-m-d.log") . '.log', $exception->getMessage() . "\n", FILE_APPEND);
}
