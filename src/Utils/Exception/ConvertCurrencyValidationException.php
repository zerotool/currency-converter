<?php

namespace App\Utils\Exception;

/**
 * Class ConvertCurrencyValidationException
 * @package App\Utils\Exception
 */
class ConvertCurrencyValidationException extends \Exception
{
    /**
     * ConvertCurrencyValidationException constructor.
     * @param string|null $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(string $message = null, int $code = 0, Throwable $previous = null)
    {
        $message = 'Error in parameters: ' . $message;
        parent::__construct($message, $code, $previous);
    }
}
