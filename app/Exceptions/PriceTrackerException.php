<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;

class PriceTrackerException extends Exception
{
    /**
     * Create an exception if price not found
     *
     * @return self
     */
    public static function priceNotFound(): self
    {
        return new self(
            message: 'Price not found.',
            code: 404
        );
    }

    /**
     * Create an exception when the server response is invalid or price cannot be retrieved
     *
     * @param int $code
     * @return self
     */
    public static function serverResponseError(int $code): self
    {
        return new self(
            message: 'Price could not be retrieved from the server response. ',
            code: $code
        );
    }
}
