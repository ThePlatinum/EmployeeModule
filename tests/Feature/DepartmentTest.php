<?php

use App\Models\Department;

$endpoint = "api/departments";

it('can get departments list', function () use ($endpoint) {
    $response = $this->get($endpoint);
    $response->assertStatus(200);

    $departments = $response->json();

    expect($departments)->toHaveKeys(['data', 'current_page']);
});

it('can create departments with correct slug and name for departments', function () use ($endpoint) {
    $departmentsData = [
        'name' => 'Development',
    ];

    $response = $this->post($endpoint, $departmentsData);
    $response->assertStatus(201);
    $response->assertJson($departmentsData);

    $department = $response->json();

    expect($department)->toHaveKeys(['name', 'slug']);

    expect($department['name'])->toBe('Development');
    expect($department['slug'])->toBe('development');
});

it('can get a single department by slug', function () use ($endpoint) {
    Department::factory()->create([
        'name' => "Development"
    ]);

    $slug = 'development';

    $response = $this->get("$endpoint/$slug");
    $response->assertStatus(200);

    $department = $response->json();
    expect($department)->toHaveKeys(['slug']);


    expect($department['slug'])->toBe($slug);
});

it('can edit a single department', function () use ($endpoint) {
    Department::factory()->create([
        'name' => "Development"
    ]);

    $slug = 'development';

    $response = $this->patch("$endpoint/$slug", [
        'name' => "Sales Team"
    ]);

    $response->assertStatus(200);
    $department = $response->json();

    expect($department['slug'])->toBe("sales-team");
});

it('can delete a single department', function () use ($endpoint) {
    $department = Department::factory()->create([
        'name' => 'Development',
    ]);

    $response = $this->delete("$endpoint/{$department->slug}");
    $response->assertStatus(204);

    $this->get("$endpoint/{$department->slug}")->assertStatus(404);
});
