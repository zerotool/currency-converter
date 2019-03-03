<?php

namespace App\Utils\RateFetcher;

use App\Entity\CurrencyRate;
use App\Utils\CurrencyRatesConverter;

/**
 * Class CbrRateFetcher
 * @package App\Utils\RateFetcher
 */
class CbrRateFetcher extends RateFetcher
{
    protected $url = 'https://www.cbr.ru/scripts/XML_daily.asp';

    /**
     * @param $data
     * @return mixed
     */
    public function getDataElements($data)
    {
        return $data->Valute;
    }

    /**
     * @param $dataElement
     * @return CurrencyRate|mixed
     */
    public function getCurrencyRateFromDataElement($dataElement)
    {
        $currencyRate = new CurrencyRate();
        $currencyRate->setCurrencyCode($dataElement->CharCode);
        $currencyRate->setRate(
            $this->getRateFromValue($dataElement->Value)
        );
        return $currencyRate;
    }

    /**
     * @param $value
     * @return string
     */
    public function getRateFromValue($value)
    {
        return number_format(
            CurrencyRatesConverter::BASE_CURRENCY_RATE / $this->normalizeValue($value),
            CurrencyRatesConverter::CURRENCY_RATE_PRECISION,
            static::DECIMAL_SEPARATOR,
            ''
        );
    }

    /**
     * @param $value
     * @return mixed
     */
    public function normalizeValue($value)
    {
        return str_replace(',', static::DECIMAL_SEPARATOR, $value);
    }
}

