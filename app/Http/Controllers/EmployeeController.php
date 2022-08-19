<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;      
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use APP\Employee;

class EmployeeController extends Controller
{
    public function addEmployee(Request $request)
    {
       $validator = Validator::make($request->all(), [
            'name'         => 'required|max:120',
            'email'        => 'required|email',
            'phone_number' => 'required|digits:10',
            'join_date'    => 'required|date_format:Y-m-d'
        ]);
        if($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        Employee::insert([
            'name'         =>$request->name,
            'email'        =>$request->email,
            'phone_number' =>$request->phone_number,
            'join_date'    =>$request->join_date,
            'leave_date'   =>$request->leave_date,
            'regignation'  =>$request->regignation,
            'status'       =>Employee::WORKING
        ]);

        return [
            'status'  =>200,
            'message' => "record add successfully"
        ];
    }


    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);
        if($validator->fails())
        {
            return response()->json($validator->errors(), 422);
        }

        $employee = Employee::find($request->id);
        if(!empty($request->name))
        {
            $employee->name=$request->name;
        }
        $employee->email=$request->email;
        $employee->phone_number=$request->phone_number;
        $employee->join_date=$request->join_date;
        $result = $employee->save();

        return [

            'status'  =>200,
            'message' => "Employee Has Been Updated Successfully"
        ];
    }

    public function destroy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);
        if($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $employee = Employee::find($request->id);
        $result  = $employee->delete();

        return [

            'status'  =>200,
            'message' => "Employee Has Been Deleted Successfully"
        ];

    }

    public function getEmployee(Request $request)
    {

        $data = DB::table('employee');
           
        if(!empty($request->from)) {
            $data->where('join_date' , '>=', $request->from);
        }

        if(!empty($request->to)) {
            $data->where('join_date' , '<', $request->to);
        }

        if(isset($request->status)){
            $data->where('status' , '=', $request->status);
        }
        $data = $data->get();
        return[

            'status'=> 200,
            'record'=> $data

        ];

    }
}
