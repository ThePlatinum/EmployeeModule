<?php

use App\Models\Department;
use App\Models\Employee;
use App\Models\User;

it("can get department with employees", function () {
    $this->actingAs(User::factory()->create());

    $departmentCreated = Department::factory()->create();

    $response = $this->get("api/departments/$departmentCreated->slug");

    $department = $response->json();
    expect($department['employees'])->toBeArray();
});

it("can attach a single employee to a department", function () {
    $this->actingAs(User::factory()->create());

    $departmentCreated = Department::factory()->create();
    $employeeCreated = Employee::factory()->create();

    $response = $this->post("api/departments/employees/$departmentCreated->slug", [
        'employee_id' => $employeeCreated->id
    ]);
    $response->assertStatus(200);
});

it("can attach mulitple employees to a department at once", function () {
    $this->actingAs(User::factory()->create());

    $departmentCreated = Department::factory()->create();
    $employeeCreated = Employee::factory(2)->create();

    $response = $this->post("api/departments/employees/$departmentCreated->slug?multiple=true", [
        'employee_id' => $employeeCreated->pluck('id')->toArray()
    ]);
    $response->assertStatus(200);
});

it("can update list of employees of a department", function () {
    $this->actingAs(User::factory()->create());

    $departmentCreated = Department::factory()->create();
    $employeeCreated = Employee::factory(5)->create();

    $response = $this->patch("api/departments/employees/$departmentCreated->slug", [
        'employee_id' => $employeeCreated->pluck('id')->toArray()
    ]);
    $response->assertStatus(200);
});
