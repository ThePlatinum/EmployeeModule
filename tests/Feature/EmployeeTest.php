<?php

use App\Models\Employee;

$endpoint = "api/employees";

it('can get employees list', function () use ($endpoint) {
    $response = $this->get($endpoint);
    $response->assertStatus(200);

    $employees = $response->json();

    expect($employees)->toHaveKeys(['data', 'current_page']);
});

it('can create employees', function () use ($endpoint) {
    $employeesData = [
        'first_name' => 'Emmanuel',
        'email' => 'emmanuel@gmail.com',
    ];

    $response = $this->post($endpoint, $employeesData);
    $response->assertStatus(201);
    $response->assertJson($employeesData);

    $employee = $response->json();

    expect($employee)->toHaveKeys(['first_name', 'email']);
    expect($employee['first_name'])->toBe($employeesData['first_name']);
    expect($employee['email'])->toBe($employeesData['email']);
});

it('can get a single employee by id', function () use ($endpoint) {
    $employeeCreated = Employee::factory()->create();

    $response = $this->get("$endpoint/$employeeCreated->id");
    $response->assertStatus(200);

    $employee = $response->json();

    expect($employee)->toHaveKeys(['id']);
    expect($employee['id'])->toBe($employeeCreated['id']);
});

it('can edit a single employee', function () use ($endpoint) {
    $employeeCreated = Employee::factory()->create();

    $response = $this->put("$endpoint/$employeeCreated->id", [
        'first_name' => "Emmanuel",
        'last_name' => $employeeCreated['last_name'],
        'email' => $employeeCreated['email'],
    ]);

    $response->assertStatus(200);
    $employee = $response->json();

    expect($employee['first_name'])->toBe("Emmanuel");
});

it('can delete a single employee', function () use ($endpoint) {
    $employeeCreated = Employee::factory()->create();

    $response = $this->delete("$endpoint/{$employeeCreated->id}");
    $response->assertStatus(204);

    expect($response->getContent())->toBeEmpty();

    $this->get("$endpoint/{$employeeCreated->id}")->assertStatus(404);
});

it('has correct full name', function () use ($endpoint) {
    $employeeCreated = Employee::factory()->create([
        'first_name' => "Emmanuel",
        'last_name' => "Adesina"
    ]);

    $response = $this->get("$endpoint/{$employeeCreated->id}");
    $employee = $response->json();

    expect($employee['full_name'])->toBe("Emmanuel Adesina");
});
