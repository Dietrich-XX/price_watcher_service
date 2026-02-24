<?php

declare(strict_types=1);

namespace App\Http\Actions\Web\EmailVerifications;

use App\Interfaces\Services\EmailVerifications\EmailVerifierInterface;
use Illuminate\Contracts\View\View;

readonly class EmailVerifyAction
{
    public function __construct(protected EmailVerifierInterface $emailVerifier)
    {}

    /**
     * @param string $token
     * @return View
     */
    public function __invoke(string $token): View
    {
        $this->emailVerifier->verify($token);

        return view('email_verifications.verify_success');
    }
}
