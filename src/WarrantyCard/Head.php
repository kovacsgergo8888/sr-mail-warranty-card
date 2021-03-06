<?php
/**
 * Created by PhpStorm.
 * User: kgergo
 * Date: 2019.03.02.
 * Time: 11:37
 */

namespace WarrantyCard;


class Head extends AbstractPdf implements IWarrantyCardPart
{


    /**
     * @throws \Exception
     */
    public function addPart()
    {
        $html = file_get_contents($this->templateDir . "/Head.html");
        $orderDate = new \DateTime($this->orderData["dateCreated"]);
        $this->orderData["orderDate"] = $orderDate->format("Y. m. d. H:i:s");

        foreach (array_keys($this->orderData) as $placeHolder) {
            if (is_string($this->orderData[$placeHolder])) {
                $html = str_replace("[$placeHolder]", $this->orderData[$placeHolder], $html);
            }
        }

        $this->pdf->writeHTML($html);
    }
}