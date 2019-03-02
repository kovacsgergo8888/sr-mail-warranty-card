<?php
/**
 * Created by PhpStorm.
 * User: kgergo
 * Date: 2019.03.02.
 * Time: 12:12
 */

namespace WarrantyCard;


class ProductList extends AbstractPdf implements IWarrantyCardPart
{

    public function addPart()
    {
        $this->pdf->writeHTML(
<<<EOD
Termékek:<br>
<table>
    <tr>
        <td>Név</td>
        <td>Garancia</td>
        <td>Garancia lejárat</td>
        <td>Cikkszám</td>
        </tr>
EOD
);

        foreach ($this->orderData["orderProducts"]["orderProduct"] as $orderProduct) {
            $this->pdf->writeHTML("
            <tr>
                <td>" .$orderProduct["name"] . "</td>
                <td>18 hónap</td>
                <td>KI KELL SZÁMOLNI A DÁTUMOT</td>
                <td>" .$orderProduct["sku"] . "</td>
            </tr>");
        }

        $this->pdf->writeHTML("</table>");
    }
}