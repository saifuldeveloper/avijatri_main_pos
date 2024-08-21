<?php

namespace App\Http\Controllers\Main;

use App\Models\Employee;
use App\Models\AccountBook;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Account;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

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
        if ($request->hasFile('image')) {
            $image     = $request->file("image");
            $extension = $image->getClientOriginalExtension();
            $imageName = uniqid() . '.' . $extension;
            $manager   = new ImageManager(new Driver());
            $image     = $manager->read($image);
            // $image->resize(300, 200);
            $image->save('images/staff-image/' . $imageName);
            $employee->image = $imageName;
        }
        $employee->salary =$request->salary;
        $employee->save();

        $account       = new Account;
        $account->id   = $employee->id;
        $account->type = 'employee';
        $account->name = $employee->name;
        $account->save();

        $accountBook = new AccountBook;
        $accountBook->account_id = $employee->id;
        $accountBook->account_type = 'employee';
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
        $employee->load('entries');
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
        if ($request->hasFile('image')) {
            $image     = $request->file("image");
            $extension = $image->getClientOriginalExtension();
            $imageName = uniqid() . '.' . $extension;
            $manager   = new ImageManager(new Driver());
            $image     = $manager->read($image);
            $image->save('images/staff-image/' . $imageName);
            $employee->image = $imageName;
        }
        $employee->salary =$request->salary;
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

    public function forceDelete($id)
    {
        $employee = Employee::onlyTrashed()->find($id);
        $employee->forceDelete();
        return collect(['success' => 'স্টাফের তথ্য স্থায়ীভাবে মুছে ফেলা হয়েছে।']);
    }

    public function restore($id)
    {
        $employee = Employee::onlyTrashed()->find($id);
        $employee->restore();
        return collect(['success' => 'স্টাফের তথ্য পুনরুদ্ধার করা হয়েছে।']);
    }
}
