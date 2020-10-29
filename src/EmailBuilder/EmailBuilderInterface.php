<?php


namespace EmailBuilder;


use PHPMailer\PHPMailer\PHPMailer;

interface EmailBuilderInterface
{
    /**
     * @return PHPMailer
     */
    public function buildEmail();
}