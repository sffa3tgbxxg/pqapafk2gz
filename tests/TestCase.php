<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Testing\TestResponse;

abstract class TestCase extends BaseTestCase
{
    use FillData;

    public static bool $migrated = false;
    public const POST = "post";
    public const GET = "get";
    public const PUT = "put";

    protected function setUp(): void
    {
        parent::setUp();

        if (!static::$migrated) {
            Artisan::call('migrate:fresh');
            static::$migrated = true;
            $this->fillData();
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

        return $this->withHeaders(['Authorization' => 'Bearer ' . $token, 'Content-Type' => 'application/json'])
            ->$method('/api/' . $url, $data);
    }

}
