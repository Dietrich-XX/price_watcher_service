<?php

declare(strict_types=1);

namespace App\Interfaces\Services\EmailVerifications;

interface EmailVerifierInterface
{
    /**
     * @param string $verificationToken
     * @return bool
     */
    public function verify(string $verificationToken): bool;
}
