<?php


use Tests\TestCase;

class ServiceTest extends TestCase
{
    /**
     * @throws \Throwable
     */
    public function test_create_service_without_subscription(): void
    {
        $response = $this->requestAsUser(self::POST, 'services', login: 'adminTest');
        $response->assertStatus(403);
        $response->assertJson(["message" => "This action is unauthorized."]);
    }

    public function test_create_service_with_subscription(): void
    {
        $response = $this->requestAsUser(self::POST, 'services', [
            'name' => 'Тестовый сервис',
        ]);
        $response->assertStatus(201);
    }

    public function test_show_services(): void
    {
        $response = $this->requestAsUser(self::GET, 'services');
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'data' => [
                [
                    'id' => 1,
                    'name' => 'TestService',
                    'role' => 'Владелец',
                ]
                ,
                [
                    'id' => 2,
                    'name' => 'Тестовый сервис',
                    'role' => 'Владелец',
                ]
            ]
        ]);
    }

    public function test_update_service(): void
    {
        $response = $this->requestAsUser(self::PUT, 'services/2', ['name' => 'Поменял название']);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'data' => [
                'id' => 2,
                'name' => 'Поменял название',
                'role' => 'Владелец',
            ]
        ]);
    }

}
