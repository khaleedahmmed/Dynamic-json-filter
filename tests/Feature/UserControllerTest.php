<?php

use Tests\TestCase;
use Illuminate\Support\Facades\Storage;

class UserControllerTest extends TestCase
{

    public function testIndexWithProviderFilter()
    {
        $response = $this->get('/api/v1/users?provider=DataProviderX');

        $response->assertStatus(200)
            ->assertJsonCount(3); // The count of records
    }

    public function testIndexWithStatusCodeFilter()
    {
        $response = $this->get('/api/v1/users?statusCode=authorised');

        $response->assertStatus(200)
            ->assertJsonCount(3); // The correct count of records
    }

    public function testIndexWithBalanceMinFilter()
    {
        $response = $this->get('/api/v1/users?balanceMin=200');

        $response->assertStatus(200)
            ->assertJsonCount(6); // The correct count of records
    }

    public function testIndexWithBalanceMaxFilter()
    {
        $response = $this->get('/api/v1/users?balanceMax=500');

        $response->assertStatus(200)
            ->assertJsonCount(5);
    }

    public function testIndexWithCurrencyFilter()
    {
        $response = $this->get('/api/v1/users?currency=USD');

        $response->assertStatus(200)
            ->assertJsonCount(2);
    }
}
