<?php

use App\Models\User;

test('customer directory requires a token', function () {
    $this->getJson('/api/customers')->assertUnauthorized();
});

test('customer directory lists shop accounts without passwords', function () {
    config(['integration.token' => 'secret-token']);

    User::factory()->create([
        'name' => 'علی مشتری',
        'phone' => '09120000000',
        'password' => 'password12',
    ]);

    $this->withToken('secret-token')
        ->getJson('/api/customers')
        ->assertOk()
        ->assertJsonPath('data.0.phone', '09120000000')
        ->assertJsonMissingPath('data.0.password');
});
