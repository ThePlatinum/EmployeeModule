<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    private $rules = [
        'name' => 'required|string|max:150|unique:departments,name'
    ];

    /**
     * Display a listing of the department.
     *
     * With options for number of results, n and page number
     */
    public function index()
    {
        $page = max(1, request()->query('page', 1));
        $n = min(100, max(1, request()->query('n', 30)));

        $departments = Department::paginate($n, ['*'], 'page', $page);

        return $departments;
    }

    /**
     * Store a newly created department in storage.
     */
    public function store(Request $request)
    {
        $request->validate($this->rules);
        $department = Department::create($request->only(['name']));

        return $department;
    }

    /**
     * Display the specified department.
     */
    public function show(Department $department)
    {
        return $department->load('employees');
    }

    /**
     * Update the specified department in storage.
     */
    public function update(Request $request, Department $department)
    {
        $request->validate($this->rules);
        $department->update($request->only(['name']));

        return $department;
    }

    /**
     * Remove the specified department from storage.
     */
    public function destroy(Department $department)
    {
        $department->delete();
        return response('', 204);
    }

    /**
     * Attach an employee or a list of employees to a department.
     */
    public function attachEmployee(Request $request, Department $department)
    {
        $multiple = request()->query('multiple', false);

        if ($multiple) {
            $request->validate([
                'employee_id' => 'required|array',
                'employee_id.*' => 'required|exists:employees,id',
            ]);
        } else {
            $request->validate([
                'employee_id' => 'required|exists:employees,id',
            ]);
        }

        $department->employees()->attach($request->employee_id);

        return response(['message' => 'Employee attached to the department successfully']);
    }

    /**
     * Update the list of employees for a department.
     */
    public function updateEmployees(Request $request, Department $department)
    {
        $request->validate([
            'employee_id' => 'required|array|exists:employees,id',
        ]);

        $employeeIds = $request->input('employee_id');
        $department->employees()->sync($employeeIds);

        return response(['message' => 'Employees updated for the department successfully']);
    }
}
