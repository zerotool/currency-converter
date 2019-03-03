<?php

namespace App\Utils\RateFetcher;

use App\Entity\CurrencyRate;

/**
 * Class EcbRateFetcher
 * @package App\Utils\RateFetcher
 */
class EcbRateFetcher extends RateFetcher
{
    protected $url = 'https://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml';

    /**
     * @param $data
     * @return mixed
     */
    public function getDataElements($data)
    {
        return $data->Cube->Cube->Cube;
    }

    /**
     * @param $dataElement
     * @return CurrencyRate|mixed
     */
    public function getCurrencyRateFromDataElement($dataElement)
    {
        $currencyRate = new CurrencyRate();
        $currencyRate->setCurrencyCode($dataElement['currency']);
        $currencyRate->setRate($dataElement['rate']);
        return $currencyRate;
    }
}
