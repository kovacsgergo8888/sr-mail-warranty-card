<?php

require_once __DIR__ . "/../vendor/autoload.php";

use Config\Config;
use Email\Email;
use PHPMailer\PHPMailer\PHPMailer;
use ShopRenterApi\Batch\BatchProductNumberAttributeValues;
use ShopRenterApi\OrderDataApi;
use WarrantyCard\WarrantyCard;


if (empty($_POST["data"])) {
    exit("no data");
}
try {
    $data = \json_decode($_POST["data"], true);
    $dataFromHook = $data["orders"]["order"][0];

    $config = new Config();
    $configData = $config->getConfig();

    $orderDataApi = new OrderDataApi($config);

    $dataFromApi = $orderDataApi->getOrderData($dataFromHook["innerId"]);

    $dataFromHook["dateCreated"] = $dataFromApi["dateCreated"];

    unset($dataFromApi);

    $productNumberAttributes = new BatchProductNumberAttributeValues($config);
    $productNumberAttributes->setAttributeId($configData["productWarrantyAttributeId"]);
    $productNumberAttributes->setProductIds(
        array_map(function ($product) {
            return $product["innerId"];
        }, $dataFromHook["orderProducts"]["orderProduct"]));
    $dataFromHook["warranties"] = $productNumberAttributes->getValuesByProductId();
    $dataFromHook["defaultWarrantyTime"] = $configData["defaultWarrantyTime"];

    $warranty = new WarrantyCard(new \TCPDF(), $dataFromHook);
    $warranty->generateWarrantyCard();

    $email = new Email($dataFromHook);

    $mail = new PHPMailer();
    $mail->CharSet = "UTF-8";

    $mail->isMail();
    $mail->setFrom($configData["emailFrom"], $configData["emailFromName"]);
    $mail->addAddress($dataFromHook["email"]);

    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = $email->getTemplate("Subject.txt");
    $mail->Body = $email->getTemplate("EmailHtml.html");
    $mail->AltBody = $email->getTemplate("EmailText.txt");

    $mail->addStringAttachment(
        $warranty->getPdf()->Output("warranty.pdf", "S"),
        $email->placeHolderReplacer($configData["warrantyFilename"])
    );

    $mail->send();
} catch (\Exception $exception) {
    file_put_contents(__DIR__ . "/../log/" . date("Y-m-d.log"), $exception->getMessage() . "\n", FILE_APPEND);
}
