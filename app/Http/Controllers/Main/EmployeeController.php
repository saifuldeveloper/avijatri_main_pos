<?php

namespace App\Http\Controllers\Main;

use App\Models\Employee;
use App\Models\AccountBook;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Employee::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


    



        $employee = new Employee;
        $employee->fill($request->all());
        if($request->hasFile('image')) {
            $filename = randomImageFileName();
            Image::make($request->file('image'))->save(imagePath($filename));
            $employee->image = $filename;
        }
        $employee->save();

        $accountBook =new AccountBook;
        $accountBook->account_id =$employee->id;
        $accountBook->account_type ='employee';
        $accountBook->save();


        // $employee->accountBooks()->save(new AccountBook());

        return $employee;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function show(Employee $employee)
    {
        $employee->append('current_book');
        $employee->current_book->append('entries');
        return $employee;
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
        $employee->fill($request->all());
        if($request->hasFile('image')) {
            $filename = randomImageFileName();
            Image::make($request->file('image'))->save(imagePath($filename));
            $employee->image = $filename;
        }
        $employee->save();

        return $employee;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function destroy(Employee $employee)
    {
        $employee->delete();
        return collect(['success' => 'স্টাফের তথ্য মুছে ফেলা হয়েছে।']);
    }
}
