<?php
/**
 * Created by PhpStorm.
 * User: kovacsgergely
 * Date: 2018.01.28.
 * Time: 15:14
 */

use PHPMailer\PHPMailer\PHPMailer;

require_once __DIR__ . "/../vendor/autoload.php";

$mail = new PHPMailer();

$mail->isMail();
try {
    $mail->setFrom("asdf@asdf.hu", "asdf asdf");
    $mail->addAddress("wse.teszt.g@gmail.com");

    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = 'Here is the subject';
    $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->addAttachment(__DIR__ . "/../product_manuals/BW-S5-másolata-1.pdf", "asd.pdf");

    $mail->send();
} catch (\Throwable $throwable) {
    echo "Something went wrong: {$throwable->getMessage()}";
} catch (\Exception $exception) {
    echo "Something went wrong: {$exception->getMessage()}";
}
