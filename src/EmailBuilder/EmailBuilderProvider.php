<?php


namespace EmailBuilder;


use Config\Config;
use WarrantyCard\WarrantyCardEmailBuilder;
use WarrantyReport\WarrantyReport;
use WarrantyReport\WarrantyReportEmailBuilder;

class EmailBuilderProvider
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

    /**
     * @return WarrantyCardEmailBuilder|WarrantyReportEmailBuilder
     * @throws \Exception
     */
    public function getBuilder()
    {
        $nameSpace = $this->getByConfig();
        if ($nameSpace == NameSpaces::WARRANTY_REPORT) {
            return new WarrantyReportEmailBuilder($this->config, $this->dataFromHook);
        } else if ($nameSpace == NameSpaces::WARRANTY_CARD) {
            return new WarrantyCardEmailBuilder($this->config, $this->dataFromHook);
        }
        throw new \Exception('namespace not found');
    }
}