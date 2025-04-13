<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Currency;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $currencies = Currency::query()
            ->whereIn('code', ['USD', 'BITCOIN'])
            ->pluck('code')
            ->toArray();

        echo implode(',', $currencies);
    }
}
