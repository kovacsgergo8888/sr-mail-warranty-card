<?php
/**
 * Created by PhpStorm.
 * User: kgergo
 * Date: 2019.02.24.
 * Time: 22:13
 */

namespace WarrantyCard;

class WarrantyCard extends AbstractPdf
{

    /**
     * @var Head
     */
    protected $head;

    protected $productList;

    protected $emptyTable;

    protected $fixText;

    public function generateWarrantyCard()
    {
// set default header data
        $headerText = file_get_contents(__DIR__ . "/../../template/PageHeader.txt");
        $this->pdf->setHeaderData("", "", "", $headerText);

// set header and footer fonts
        $this->pdf->setHeaderFont([PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN]);
        $this->pdf->setFooterFont([PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA]);

// set default monospaced font
        $this->pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
        $this->pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $this->pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $this->pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
        $this->pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
        $this->pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        $this->pdf->SetFont('dejavusans', '', 10);

        $this->pdf->AddPage();

        $this->head = new Head($this->pdf, $this->orderData);
        $this->head->addPart();

        $this->productList = new ProductList($this->pdf, $this->orderData);
        $this->productList->addPart();

        $this->emptyTable = new EmptyTable($this->pdf, $this->orderData);
        $this->emptyTable->addPart();

        $this->pdf->writeHTML(file_get_contents($this->templateDir . "/FixText.html"));
    }

}