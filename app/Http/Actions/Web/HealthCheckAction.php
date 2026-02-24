<?php

declare(strict_types=1);

namespace App\Http\Actions\Web;

use Illuminate\Http\Response;

class HealthCheckAction
{
    /**
     * @return Response
     */
    public function __invoke(): Response
    {

        return response('OK', 200);
    }
}
