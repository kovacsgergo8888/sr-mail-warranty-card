<?php
/**
 * Created by PhpStorm.
 * User: kgergo
 * Date: 2019.02.24.
 * Time: 22:14
 */

class PDFGenerator
{
    /**
     * @var FPDF
     */
    private $fpdf;

    public function __construct()
    {
        $this->fpdf = new FPDF();
    }

}