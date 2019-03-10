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

        foreach ($this->orderData["orderProducts"]["orderProduct"] as $orderProduct) {

            $warrantyTime = isset($this->orderData["warranties"][$orderProduct["innerId"]])
                ? $this->orderData["warranties"][$orderProduct["innerId"]]
                : $this->orderData["defaultWarrantyTime"];

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
}