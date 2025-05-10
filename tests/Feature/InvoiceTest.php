<?php


use Tests\TestCase;

class InvoiceTest extends TestCase
{
    /**
     * @throws \Throwable
     */
    public function test_a_create_invoice(): void
    {
        $response = $this->withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => 'TestService',
        ])->postJson('/api/invoices', [
            'amount' => 5000,
            'user_nickname' => 'Test',
            'user_id' => 1,
        ]);

        dump($response->json());
        $response->assertStatus(201);

    }
}
