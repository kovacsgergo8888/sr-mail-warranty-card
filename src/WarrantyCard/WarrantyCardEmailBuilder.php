<?php


namespace WarrantyCard;


use Config\Config;
use Email\Email;
use EmailBuilder\EmailBuilderInterface;
use PHPMailer\PHPMailer\PHPMailer;

class WarrantyCardEmailBuilder implements EmailBuilderInterface
{
    /**
     * @var PHPMailer
     */
    private $phpMailer;

    /**
     * @var Config
     */
    private $config;

    /**
     * @var array
     */
    private $dataFromHook;

    /**
     * WarrantyCardEmailBuilder constructor.
     * @param PHPMailer $phpMailer
     * @param Config $config
     * @param array $dataFromHook
     */
    public function __construct(Config $config, array $dataFromHook)
    {
        $this->phpMailer = new PHPMailer();
        $this->config = $config;
        $this->dataFromHook = $dataFromHook;
    }


    /**
     * @throws \PHPMailer\PHPMailer\Exception
     */
    public function buildEmail()
    {
        $email = new Email($this->dataFromHook);

        $warranty = new WarrantyCard(new \TCPDF(), $this->dataFromHook);
        $warranty->generateWarrantyCard();
        $this->phpMailer->CharSet = "UTF-8";

        $this->phpMailer->isMail();
        $this->phpMailer->setFrom($this->config->get("emailFrom"), $this->config->get("emailFromName"));
        $this->phpMailer->addAddress($this->dataFromHook["email"]);

        $this->phpMailer->isHTML(true);                                  // Set email format to HTML
        $this->phpMailer->Subject = $email->getTemplate("Subject.txt");
        $this->phpMailer->Body = $email->getTemplate("EmailHtml.html");
        $this->phpMailer->AltBody = $email->getTemplate("EmailText.txt");

        $this->phpMailer->addStringAttachment(
            $warranty->getPdf()->Output("warranty.pdf", "S"),
            $email->placeHolderReplacer($this->config->get("warrantyFilename"))
        );

        return $this->phpMailer;
    }
}