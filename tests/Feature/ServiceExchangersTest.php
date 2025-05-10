<?php


use Tests\TestCase;

class ServiceExchangersTest extends TestCase
{
    /**
     * @throws \Throwable
     */
    public function test_add_exchanger_to_service(): void
    {
        $response = $this->requestAsUser(self::POST, 'service-exchangers',
            [
                'service_id' => 1,
                'exchanger_id' => 2,
                'api_key' => md5('123-1451-111-5hh4-h31'),
            ]);

        $response->assertStatus(201);

        $response->assertJsonFragment([
            'data' => [
                'id' => 2,
                'service_name' => 'TestService',
                'name' => 'Обмен медленно',
                'active' => true,
            ],
        ]);
    }
}
