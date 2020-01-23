<?php


namespace WarrantyReport;


class WarrantyReport
{
    /**
     * @var \TCPDF
     */
    private $pdf;

    public function __construct(\TCPDF $pdf, $html)
    {
        $this->pdf = $pdf;


// set header and footer fonts
        $this->pdf->setHeaderFont([PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN]);
        $this->pdf->setFooterFont([PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA]);

// set default monospaced font
        $this->pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
        $this->pdf->SetMargins(PDF_MARGIN_LEFT, 40, PDF_MARGIN_RIGHT);
        $this->pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $this->pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
        $this->pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
        $this->pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        $this->pdf->SetFont('dejavusans', '', 10);

        $this->pdf->AddPage();

        $this->pdf->writeHTML($html);
    }

    /**
     * @return \TCPDF
     */
    public function getPdf()
    {
        return $this->pdf;
    }
}