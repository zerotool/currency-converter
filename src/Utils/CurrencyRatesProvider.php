<?php

namespace App\Utils;

use App\Utils\RateFetcher\CbrRateFetcher;
use App\Utils\RateFetcher\EcbRateFetcher;
use App\Utils\RateFetcher\RateFetcher;
use Doctrine\ORM\EntityManager;

/**
 * Class CurrencyRatesProvider
 * @package App\Utils
 */
class CurrencyRatesProvider
{
    /** @var string */
    public $providerCode;

    /** @var RateFetcher */
    public $rateFetcher;

    /** @var EntityManager */
    public $entityManager;

    const PROVIDER_CODE_ECB = 'ecb';
    const PROVIDER_CODE_CBR = 'cbr';

    /**
     * CurrencyRatesProvider constructor.
     * @param string $providerCode
     * @param EntityManager $entityManager
     */
    public function __construct(string $providerCode, EntityManager $entityManager)
    {
        $this->providerCode = $providerCode;
        $this->entityManager = $entityManager;
    }

    /**
     * Factory method that returns fetcher of required type
     * @return CbrRateFetcher|EcbRateFetcher|RateFetcher
     */
    public function getRateFetcher()
    {
        if (empty($this->rateFetcher)) {
            switch ($this->providerCode) {
                case self::PROVIDER_CODE_CBR:
                    $this->rateFetcher = new CbrRateFetcher($this->entityManager);
                    break;
                case self::PROVIDER_CODE_ECB:
                    $this->rateFetcher = new EcbRateFetcher($this->entityManager);
                    break;
                default:
                    break;
            }
        }
        return $this->rateFetcher;
    }
}
