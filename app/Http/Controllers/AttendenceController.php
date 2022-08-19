<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Employee;
use App\Attendence;
use Carbon\Carbon;

class AttendenceController extends Controller
{
    public function employeeAttendence(Request $request)
    { 
        
        $validator = Validator::make($request->all(), [
            'employee_id'   => 'required',
            'check_type'    => 'required|in:in,out',
            
        ]);
        if($validator->fails()) {   
            return response()->json($validator->errors(), 422);
        } 
        $employee = Employee::find($request->employee_id);
        if(empty($employee)) {
            return[

                'status' => 403,
                'message' => "invalid employee_id"
            ];
        }

        $today_attendence= Attendence::firstorNew([
            'employee_id'=> $request->employee_id,
            'date'       => Carbon::now()->format('Y-m-d')
        ]);

        if($request->check_type == 'in') {
            if(!empty($today_attendence->check_in)) {
                return [
                    'status' => 403,
                    'message' => "already checked in"
                ];   
            }
        
            $today_attendence->check_in = Carbon::now()->format('Y-m-d H:i:s');
            $today_attendence->status = Attendence::CHECK_IN;
            $today_attendence->save();
             return [
                'status' => 200,
                'message' => "checked in successfully"
            ];

        }else if(!empty($today_attendence->check_type == 'out')) {
            return [
                'status' => 403,
                'message' => "already checked out"
            ]; 
        }
        $today_attendence->check_out = Carbon::now()->format('Y-m-d H:i:s');

        if (!empty($today_attendence->check_in)) {
            $check_in = $today_attendence->check_in;
            $diff_in_hours  = Carbon::now()->diffInHours($check_in);
                
            if($diff_in_hours < 8) {
                $today_attendence->status = Attendence::HALFDAY;

            }else {
                   
                $today_attendence->status = Attendence::PRESENT;

            }  
        }else{
            $today_attendence->status = Attendence::ABSENT;
        }

        $today_attendence->save();

        return [
            'status' => 200,
            'message' => "checked out successfully"
        ]; 
    }

    public function getAttendence(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'employee_id' => 'required',
        ]);
        if($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

       $count = DB::table('attendence')
            ->select('status',DB::raw('count(*) as total'))
            ->where('employee_id','=',$request->employee_id)
            ->groupBy('status'); 
        
        if(!empty($request->from)) {
            $count->where('date', '>=', $request->from);
        }
        if(!empty($request->to)) {
            $count->where('date', '<', $request->to);
        }

        $count = $count->get();

        $final_array = [];
        foreach($count as $value) {
            $final_array[Attendence::STATUS[$value->status]] = $value->total;
        }
        return[
           'status' => 200,
            'record' => [
                 'count'  => $final_array,
                ]
        ];
    }
}
