<?php

namespace App\Utils;

use App\Repository\CurrencyRateRepository;
use App\Utils\Exception\RateNotFoundException;

/**
 * Class CurrencyRatesConverter
 * @package App\Utils
 */
class CurrencyRatesConverter
{
    /** @var string */
    private $baseCurrencyCode;

    /** @var CurrencyRateRepository */
    private $currencyRateRepository;

    /** @var int Rate of the base currency */
    const BASE_CURRENCY_RATE = 1;

    /** @var int Amount of numbers after comma in the currency rates/amounts */
    const CURRENCY_RATE_PRECISION = 4;

    /**
     * CurrencyRatesConverter constructor.
     * @param $baseCurrencyCode
     * @param CurrencyRateRepository $currencyRateRepository
     */
    public function __construct($baseCurrencyCode, CurrencyRateRepository $currencyRateRepository)
    {
        $this->baseCurrencyCode = $baseCurrencyCode;
        $this->currencyRateRepository = $currencyRateRepository;
    }

    /**
     * @param $fromCurrencyCode
     * @param $toCurrencyCode
     * @param $amount
     * @return float|int
     * @throws RateNotFoundException
     * @TODO: Add Redis caching support by codes/amount key
     */
    public function convert($fromCurrencyCode, $toCurrencyCode, $amount): float
    {
        return round(
            $amount / $this->getRateByCode($fromCurrencyCode) * $this->getRateByCode($toCurrencyCode),
            static::CURRENCY_RATE_PRECISION
        );
    }

    /**
     * @param $currencyCode
     * @return int
     * @throws RateNotFoundException
     */
    public function getRateByCode($currencyCode)
    {
        if ($this->baseCurrencyCode == $currencyCode) {
            return static::BASE_CURRENCY_RATE;
        } else {
            $rate = $this
                ->currencyRateRepository
                ->findOneBy(['currency_code' => $currencyCode]);
            if (empty($rate)) {
                throw new RateNotFoundException($currencyCode);
            } else {
                return $rate->getRate();
            }
        }
    }
}
