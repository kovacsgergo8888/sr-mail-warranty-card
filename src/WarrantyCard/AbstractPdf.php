<?php
/**
 * Created by PhpStorm.
 * User: kgergo
 * Date: 2019.03.02.
 * Time: 11:38
 */

namespace WarrantyCard;


abstract class AbstractPdf
{
    /**
     * @var \TCPDF
     */
    protected $pdf;
    /**
     * @var []
     */
    protected $orderData;

    /**
     * @var string
     */
    protected $templateDir;

    public function __construct(\TCPDF $pdf, $orderData)
    {
        $this->pdf = $pdf;
        $this->orderData = $orderData;
        $this->templateDir = __DIR__ . "/../../template";
    }

    /**
     * @return \TCPDF
     */
    public function getPdf()
    {
        return $this->pdf;
    }

    public function getOrderData()
    {
        return $this->orderData;
    }
}