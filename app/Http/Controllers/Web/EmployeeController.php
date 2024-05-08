<?php

namespace App\Http\Controllers\Web;

use App\Models\Employee;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EmployeeController extends \App\Http\Controllers\Main\EmployeeController
{
    // public function __construct() {
    //     $this->middleware(['permission:manage employees'])->except(['datalist']);
    // }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $employees = parent::index();
        return view('employee.index', compact('employees'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        preventHttp();
        $employee = null;
        return view('employee.form', compact('employee'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $employee = parent::store($request);
        return back()->with('success-alert', 'নতুন স্টাফের তথ্য সংরক্ষণ করা হয়েছে।');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function show(Employee $employee)
    {

        $employee = parent::show($employee);
        $entries  = $employee->entries()->paginate(10);
        $total    = $entries->sum('total_amount');
        return view('employee.show', compact('employee','entries','total'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function edit(Employee $employee)
    {
        preventHttp();
        return view('employee.form', compact('employee'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Employee $employee)
    {
        $employee = parent::update($request, $employee);
        return back()->with('success-alert', 'স্টাফের তথ্য এডিট হয়েছে।');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function destroy(Employee $employee)
    {
        $message = parent::destroy($employee);
        return back()->with('success-alert', $message['success']);
    }

    public function datalist() {
        preventHttp();
        $model = 'employee';
        $list = Employee::all();
        return view('layouts.datalist', compact('model', 'list'));
    }
}