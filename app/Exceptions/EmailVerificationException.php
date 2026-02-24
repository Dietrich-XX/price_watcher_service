<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;

class EmailVerificationException extends Exception
{
    /**
     * Create an exception for an invalid or expired verification token
     *
     * @return self
     */
    public static function tokenNotFound(): self
    {
        return new self(
            message: 'The verification token is invalid or has expired.',
            code: 404
        );
    }

    /**
     * Create an exception when the subscriber associated with the token is not found
     *
     * @return self
     */
    public static function subscriberNotFound(): self
    {
        return new self(
            message:'The subscriber associated with this token was not found.',
            code: 404
        );
    }

    /**
     * Create an exception when the email verification process fails unexpectedly
     *
     * @return self
     */
    public static function verificationFailed(): self
    {
        return new self(
            message: 'Email verification could not be completed.',
            code: 500
        );
    }
}
