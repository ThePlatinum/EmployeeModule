<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    private $rules = [
        'first_name' => 'required|string|max:150',
        'last_name' => 'nullable|string|max:150',
        'email' => 'required|email'
    ];

    /**
     * Display a listing of the empoyees.
     */
    public function index()
    {
        $page = max(1, request()->query('page', 1));
        $n = min(100, max(1, request()->query('n', 30)));

        $employees = Employee::paginate($n, ['*'], 'page', $page);

        return $employees;
    }

    /**
     * Store a newly created empoyees in storage.
     */
    public function store(Request $request)
    {
        $request->validate($this->rules);
        $employee = Employee::create($request->only(['first_name', 'last_name', 'email']));

        return $employee;
    }

    /**
     * Display the specified empoyees.
     */
    public function show(Employee $employee)
    {
        // ->with('department')->get()
        return $employee;
    }

    /**
     * Update the specified empoyees in storage.
     */
    public function update(Request $request, Employee $employee)
    {
        $request->validate($this->rules);
        $employee->update($request->only(['first_name', 'last_name', 'email']));

        return $employee;
    }

    /**
     * Remove the specified empoyees from storage.
     */
    public function destroy(Employee $employee)
    {
        $employee->delete();
        return response('', 204);
    }
}
