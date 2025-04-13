<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Testing\TestResponse;

abstract class TestCase extends BaseTestCase
{
    public static bool $migrated = false;
    public const POST = "post";
    public const GET = "get";

    protected function setUp(): void
    {
        parent::setUp();

        if (!static::$migrated) {
            Artisan::call('migrate:fresh');
            static::$migrated = true;
            User::query()->create(['login' => 'admin', 'password' => 'passw0rd']);
        }
    }

    public static function tearDownAfterClass(): void
    {
        parent::tearDownAfterClass();

        Artisan::call('migrate:fresh');
    }

    public function requestAsUser(string $method, string $url, array $data = [], string $login = 'admin'): TestResponse
    {
        $user = User::query()->where('login', $login)->first();
        $token = $user->createToken('test_token')->plainTextToken;
        $method = "{$method}Json";

        return $this->withHeaders(['Authorization' => 'Bearer ' . $token])
            ->$method('/api/' . $url, $data);
    }
}
