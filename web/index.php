<?php

require_once __DIR__ . "/../vendor/autoload.php";

use Config\Config;
use DataCollector\DataCollector;
use Email\Email;
use PHPMailer\PHPMailer\PHPMailer;
use WarrantyCard\WarrantyCard;

error_reporting(E_ERROR);

if (empty($_POST["data"])) {
    exit("no data");
}
try {
    $data = \json_decode($_POST["data"], true);
    $config = new Config();

    $dataCollector = new DataCollector();
    $dataFromHook = $dataCollector->collectData($data, $config);

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
