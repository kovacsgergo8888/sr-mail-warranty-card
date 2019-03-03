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
        "<br><br><h3>Termékek:</h3><br>
        <table>
            <tr>
                <td>Név</td>
                <td>Garancia</td>
                <td>Garancia lejárat</td>
                <td>Cikkszám</td>
                </tr>"
        ;

        $orderCreated = new \DateTime($this->orderData["dateCreated"]);
        $warrantyEnd = $this->getWarrantyEndDate(
            new \DateTime($this->orderData["dateCreated"])
        )->format("Y. m. d.");
        foreach ($this->orderData["orderProducts"]["orderProduct"] as $orderProduct) {
            $html .=
                "<tr>
                    <td>" .$orderProduct["name"] . "</td>
                    <td>18 hónap</td>
                    <td>$warrantyEnd</td>
                    <td>" .$orderProduct["sku"] . "</td>
                </tr>"
            ;
        }

        $html .= "</table>";
        $this->pdf->writeHTML($html);
    }

    public function getWarrantyEndDate(\DateTime $dateTime)
    {
        $dateTime->setTime(23,59, 59)
            ->modify("+18 months")
            ->modify("+2 days");
        return $dateTime;
    }
}