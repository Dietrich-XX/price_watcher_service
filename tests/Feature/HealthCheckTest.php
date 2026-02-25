<?php

declare(strict_types=1);

namespace Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

class HealthCheckTest extends BaseTestCase
{
    use DatabaseTransactions;

    /**
     * @return void
     */
    public function test_health_check_returns_ok(): void
    {
        $response = $this->get('/health-check/up');

        $response->assertStatus(200)
            ->assertSeeText('OK');
    }
}
