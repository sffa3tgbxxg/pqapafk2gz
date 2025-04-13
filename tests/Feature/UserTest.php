<?php


use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * @throws \Throwable
     */
    public function test_show_user_info_without_subscription(): void
    {
        $response = $this->requestAsUser(self::GET, 'me');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'login',
                'subscription',
            ],
        ],
            ['data' =>
                [
                    'id' => 1,
                    'login' => 'admin',
                    'subscription' => false
                ]
            ]);
    }
}
