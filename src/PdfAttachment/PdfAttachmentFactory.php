<?php


namespace PdfAttachment;


use Config\Config;
use WarrantyReport\WarrantyReport;

class PdfAttachmentFactory
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @var array
     */
    private $dataFromHook;

    public function __construct(Config $config, array $dataFromHook)
    {
        $this->config = $config;
        $this->dataFromHook = $dataFromHook;
    }

    private function getByConfig ()
    {
        foreach ($this->config->get('orderStatusIds') as $config) {
            if ($this->dataFromHook['orderHistory']['status'] == $config['orderStatusId']) {
                return $config['nameSpace'];
            }
        }
        return NameSpaces::WARRANTY_CARD;
    }

    public function getPdfAttachment()
    {
        $nameSpace = $this->getByConfig();
        if ($nameSpace == NameSpaces::WARRANTY_REPORT) {
            return new WarrantyReport(new \TCPDF(), $dataFromHook);
        }
    }
}