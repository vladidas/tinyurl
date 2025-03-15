<?php

namespace Tests\Feature\Traits;

use Illuminate\Testing\TestResponse;

trait WithPerformanceChecks
{
    protected function assertResponseTimeIsAcceptable(TestResponse $response, float $maxDuration = 500): void
    {
        $duration = $response->headers->get('X-Response-Time');
        
        $this->assertNotNull(
            $duration,
            'Response time header is missing. Make sure you have the response time middleware enabled.'
        );
        
        $this->assertLessThanOrEqual(
            $maxDuration,
            floatval($duration),
            "Response time of {$duration}ms exceeds maximum allowed duration of {$maxDuration}ms."
        );
    }
} 