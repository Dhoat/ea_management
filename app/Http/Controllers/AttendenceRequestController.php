<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Employee;
use App\Attendence;
use App\AttendenceRequest;
use Carbon\Carbon;

class AttendenceRequestController extends Controller
{
    public function addAttendenceRequst(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'employee_id'  => 'required',
            'date'         =>'required |date_format:Y-m-d',
            'request_type' =>'required| in:1,2,3,4,5,6,7,8',
            'message'      =>'required',
        ]);
        if($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $employee = Employee::find($request->employee_id);
        if(empty($employee)) {
            return [
                'status' => 403,
                'message' => "invalid employee_id"
            ]; 
        }
        AttendenceRequest::insert([
            'employee_id' => $request->employee_id,
            'date'        => $request->date,
            'request_type'=> $request->request_type,
            'message'     => $request->message,
            'status'      => k AttendenceRequest::STATUS_PENDING,

        ]);

        return [
            'status' => 200,
            'message'=>"record added successfully"
        ];
        
    }
    
    public function requestCheck(Request $request)
    {
        $result = DB::table('attendence_request')
            ->leftjoin('employee', 'employee.id', '=', 'attendence_request.employee_id')
            ->select([
                'attendence_request.id',
                'employee_id',
                'employee.name as employee_name',
                'date',
                'attendence_request.status',
                'message',   
            ]);
                   

        if(!empty($request->from)) {
            $result->where('date', '>=', $request->from);
        }
        if(!empty($request->to)) {
            $result->where('date', '<', $request->to);
        }
        if(!empty($request->employee_id)) {
            $result->where('employee_id', '<', $request->employee_id);
        }

        if(isset($request->status)) {
            $result->where('status', '=', $request->status);
        }

        $result = $result->get();

        foreach($result as $r) {
            $r->status= AttendenceRequest::STATUS[$r->status];    
        }

        return [
            'status' => 200,
            'message' => $result
        ]; 
    }

    public function requestApprovel(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'request_id'     => 'required',
            'request_status' =>'required| in:1,2',
            'reason'         =>'required',
        ]);
        if($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

       $attendence_request = AttendenceRequest::find($request->request_id);
       if(empty($attendence_request)) {
          return [
            'status' => 403,
            'message' => "invalid id"
        ]; 
       }
       if($request->request_status == 2) {
           $attendence_request->status = AttendenceRequest::STATUS_REJECT;
           $attendence_request->reason = $request->reason;
           $attendence_request->save();
            
            return [
                'status' => 200,
                'message'=>"Attendence Rejected"
            ];
       
       }else {
           
            $attendence_request->status = AttendenceRequest::STATUS_APPROVE;
            $attendence_request->status = $request->reason;
            $attendence_request->save();

            Attendence::where([
                'employee_id' => $attendence_request->employee_id,
                'date'        => $attendence_request->date,
            ])->update([
                'status' => Attendence::PRESENT,
            ]);

            return [
                'status' => 200,
                'message'=>"Attendence Approved"
            ];

       }

    }
}

