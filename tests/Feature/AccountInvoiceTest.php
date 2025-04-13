<?php


use Tests\TestCase;

class AccountInvoiceTest extends TestCase
{
    /**
     * @throws \Throwable
     */
    public function test_create_account_invoice_no_entry_into_account(): void
    {
        $response = $this->withHeaders(['Accept' => 'application/json'])->post('/api/account/payment');
        $response->assertStatus(401);
    }

    public function test_create_account_invoice(): void
    {
        $response = $this->requestAsUser(self::POST, 'account/payment');
        $response->assertStatus(201);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'requisites',
                'amount_rub',
                'amount_btc',
                'status',
                'created_at',
                'expiry_at',
            ],
        ]);
    }

}
