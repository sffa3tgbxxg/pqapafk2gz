<?php

namespace Tests\Feature;

use Tests\TestCase;

class AuthTest extends TestCase
{
    /**
     * @throws \Throwable
     */
    public function test_registration_user(): void
    {
        $response = $this->post("/api/auth/register", [
            'login' => 'adminTest',
            'password' => 'passw0rd',
            'password_confirmation' => 'passw0rd',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'token',
        ]);
    }

    public function test_login_user(): void
    {
        $response = $this->post('/api/auth/login', [
            'login' => 'adminTest',
            'password' => 'passw0rd',
        ]);
        $response->assertStatus(200);

        $response->assertJsonStructure([
            'token',
        ]);
    }
}
