<?php


namespace WarrantyReport;


use Config\Config;
use Email\Email;
use EmailBuilder\EmailBuilderInterface;
use EmailBuilder\NameSpaces;
use PHPMailer\PHPMailer\PHPMailer;

class WarrantyReportEmailBuilder implements EmailBuilderInterface
{
    /**
     * @var Config
     */
    private $config;

    private $dataFromHook;

    /**
     * WarrantyReportEmailBuilder constructor.
     * @param Config $config
     * @param $dataFromHook
     */
    public function __construct(Config $config, $dataFromHook)
    {
        $this->config = $config;
        $this->dataFromHook = $dataFromHook;
    }


    /**
     * @inheritDoc
     */
    public function buildEmail()
    {
        $phpMailer = new PHPMailer();
        $email = new Email($this->dataFromHook);
        $email->setTemplateDir(__DIR__ . "/../../template_WarrantyReport");
        $nameSpaceConfig = [];
        foreach ($this->config->get('orderStatusIds') as $config) {
            if ($config['nameSpace'] == NameSpaces::WARRANTY_REPORT) {
                $nameSpaceConfig = $config;
            }
        }
        $warrantyReport = new WarrantyReport(new \TCPDF(), $email->getTemplate('PDF.html'));
        $phpMailer->isMail();
        $phpMailer->CharSet = 'UTF-8';
        $phpMailer->setFrom($this->config->get('emailFromName'));
        $phpMailer->addAddress($this->dataFromHook['email']);
        $phpMailer->isHTML(true);
        $phpMailer->Subject = $email->getTemplate('Email/Subject.txt');
        $phpMailer->Body = $email->getTemplate('Email/EmailHtml.html');
        $phpMailer->AltBody = $email->getTemplate('Email/EmailText.txt');
        $phpMailer->addStringAttachment(
            $warrantyReport->getPdf()->Output('warranty_report.pdf', 'S'),
            $email->placeHolderReplacer($nameSpaceConfig['pdf_filename'])
        );
        return $phpMailer;
    }
}