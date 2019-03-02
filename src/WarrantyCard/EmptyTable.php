<?php
/**
 * Created by PhpStorm.
 * User: kgergo
 * Date: 2019.03.02.
 * Time: 12:34
 */

namespace WarrantyCard;


class EmptyTable extends AbstractPdf implements IWarrantyCardPart
{

    public function addPart()
    {
        $this->pdf->writeHTML(file_get_contents($this->templateDir . "/EmptyTable.html"));
    }
}