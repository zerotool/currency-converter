<?php

namespace App\Utils\Exception;

/**
 * Class RateNotFoundException
 * @package App\Utils\Exception
 */
class RateNotFoundException extends \Exception
{
    /**
     * RateNotFoundException constructor.
     * @param string|null $currencyCode
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(string $currencyCode = null, int $code = 0, Throwable $previous = null)
    {
        if ($currencyCode) {
            $message = 'Unable to find currency rate for currency code ' . $currencyCode;
        } else {
            $message = 'Please set currency code';
        }
        parent::__construct($message, $code, $previous);
    }
}
