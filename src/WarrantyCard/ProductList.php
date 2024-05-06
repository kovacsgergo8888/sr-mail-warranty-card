<?php
/**
 * Created by PhpStorm.
 * User: kgergo
 * Date: 2019.03.02.
 * Time: 12:12
 */

namespace WarrantyCard;


use ShopRenterApi\ApiCall;

class ProductList extends AbstractPdf implements IWarrantyCardPart
{

    /**
     * @throws \Exception
     */
    public function addPart()
    {

        $html =
        '<br><br><h3>Termékek:</h3><br>
        <table cellpadding="5" border="1">
            <tr>
                <td width="35%"><b>Név</b></td>
                <td width="14%"><b>Garancia</b></td>
                <td width="16%"><b>Garancia lejárat</b></td>
                <td width="20%"><b>Cikkszám</b></td>
                <td width="15%">Bruttó ár</td>
                </tr>'
        ;

        foreach ($this->orderData["orderProducts"]["orderProduct"] as $orderProduct) {

            $productId = $this->orderData["realProductIds"][$orderProduct["innerId"]];
            
            $grossPrice = $this->calculateGrossPrice($orderProduct);

            $warrantyTime = isset($this->orderData["warranties"][$productId])
                ? $this->orderData["warranties"][$productId]
                : $this->getDefaultWarrantyTime($grossPrice);

            $warrantyEnd = $this->getWarrantyEndDate(
                new \DateTime($this->orderData["dateCreated"]),
                $warrantyTime
            )->format("Y. m. d.");

            $html .=
                "<tr>
                    <td>" .$orderProduct["name"] . "</td>
                    <td>$warrantyTime hónap</td>
                    <td>$warrantyEnd</td>
                    <td>" .$orderProduct["sku"] . "</td>
                    <td>" . $this->formatPrice($grossPrice) . "</td>
                </tr>"
            ;
        }

        $html .= "</table>";
        $this->pdf->writeHTML($html);
    }

    public function getWarrantyEndDate(\DateTime $dateTime, $warrantyTime)
    {
        $dateTime->setTime(23,59, 59)
            ->modify("+$warrantyTime months")
            ->modify("+2 days");
        return $dateTime;
    }

    private function calculateGrossPrice($orderProduct)
    {
        return $orderProduct['price'] * (1 + ($orderProduct['taxRate'] / 100));
    }
    
    private function formatPrice($price)
    {
        return number_format($price, 0, ',', '.') . ' Ft';
    }

    private function getDefaultWarrantyTime($grossPrice)
    {
        if ($grossPrice < 10000) {
            return 12;
        }
        return 24;
    }
}