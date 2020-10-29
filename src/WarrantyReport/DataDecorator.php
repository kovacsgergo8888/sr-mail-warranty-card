<?php


namespace WarrantyReport;


class DataDecorator
{
    /**
     * @param $dataFromHook
     * @throws \Exception
     */
    public function addExtraData(&$dataFromHook)
    {
        $reportedProduct = $this->findProductFromComment(
            $dataFromHook['orderProducts']['orderProduct'],
            $dataFromHook['orderHistory']['comment']
        );
        if ($reportedProduct) {
            $dataFromHook['reportedProductName'] = $reportedProduct['name'];
            $dataFromHook['reportedProductPrice'] =
                number_format(
                    $reportedProduct['price'] * (1 + ($reportedProduct['taxRate'] / 100)),
                    0,
                    ',',
                    '.'
                )
                . ' ' . $reportedProduct['currency'];
        }

        $dateCreated = new \DateTime($dataFromHook['dateCreated']);
        $dateCreated->modify('+4 days');
        $dataFromHook['completedDate'] = $dateCreated->format('Y. m. d.');

        $reportDate = new \DateTime();
        $dataFromHook['reportDate'] = $reportDate->format('Y. m. d.');

        $replaceDate = new \DateTime();
        $reportDate->modify('+4 days');
        $dataFromHook['replaceDate'] = $reportDate->format('Y. m. d.');

        $dataFromHook['reportDescription'] = $this->getReportDescription($dataFromHook['orderHistory']['comment']);
    }

    private function findProductFromComment($orderProducts, $comment)
    {
        $reportedProduct = false;
        preg_match('/\[sku:(.*)\]/', strip_tags($comment), $matches);
        $sku = empty($matches[1]) ? false : trim($matches[1]);
        foreach ($orderProducts as $product) {
            if ($product['sku'] == $sku) {
                $reportedProduct = $product;
            }
        }
        return $reportedProduct;
    }

    private function getReportDescription($comment)
    {
        preg_match('/\[hiba:(.*)\]/', strip_tags($comment), $matches);
        return empty($matches[1]) ? '' : trim($matches[1]);
    }
}