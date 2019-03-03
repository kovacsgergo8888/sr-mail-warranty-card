<?php

require_once __DIR__ . "/../vendor/autoload.php";

use PHPMailer\PHPMailer\PHPMailer;
use ShopRenterApi\OrderDataApi;
use WarrantyCard\WarrantyCard;


if(empty($_POST["data"])) {
    exit("no data");
}

$data = \json_decode($_POST["data"], true);
$dataFromHook = $data["orders"]["order"][0];

$orderDataApi = new OrderDataApi();

$dataFromApi = $orderDataApi->getOrderData($dataFromHook["innerId"]);

$dataFromHook["dateCreated"] = $dataFromApi["dateCreated"];

unset($dataFromApi);

$warranty = new WarrantyCard(new \TCPDF(), $dataFromHook);
$warranty->generateWarrantyCard();

$mail = new PHPMailer();
try {
    $mail->isMail();
    $mail->setFrom("asdf@asdf.hu", "asdf asdf");
    $mail->addAddress($dataFromHook["email"]);

    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = 'Here is the subject';
    $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->addStringAttachment(
        $warranty->getPdf()->Output("warranty.pdf", "E"),
        "warranty.pdf"
    );

    $mail->send();
} catch (\Exception $exception) {
    file_put_contents(__DIR__ . "/../log/" . date("Y-m-d.log"), $exception->getMessage() . "\n", FILE_APPEND);
}
