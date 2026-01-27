<?php

use Illuminate\Support\Facades\Config;

it('returns liveness ok on /health', function () {
    $response = $this->get('/health');
    $response->assertStatus(200);
    $response->assertJson(['status' => 'ok']);
});

it('returns readiness with checks on /health/ready', function () {
    $response = $this->get('/health/ready');
    $response->assertStatus(200);
    $response->assertJsonStructure(['status', 'checks']);
    $json = $response->json();
    expect(array_key_exists('db', $json['checks']))->toBeTrue();
    expect(array_key_exists('cache', $json['checks']))->toBeTrue();
});
