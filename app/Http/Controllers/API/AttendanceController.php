<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Recruitment;
use App\Models\Option;
use App\Models\Log as LogDB;
use App\Models\Employee;
use App\Models\Attendance;
use Illuminate\Support\Carbon;
use Validator;
use Str;

class AttendanceController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function attendance(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required'
        ]);

        $option = Option::select('id')
            ->whereType('ATTENDANCES')
            ->whereName('ON SITE BARCODE')
            ->first();

        $email = $request->email;
        // $time_in = $request->timeIn;
        // $time_out = $request->timeOut;
        $time = $request->time;

        if ($validator->fails()) :
            $messages = $validator->errors()->all();
            $msg = $messages[0];

            $data = ['response_message' => $msg];
            return response()->json($data, 400);
        else :
            //check employee
            $employee = Employee::whereEmail($email)->first();

            if ($employee) :
                $record = Attendance::whereEmployee_id($employee->id)
                    ->whereDate('time_in', Carbon::today())->first();

                if (!$record) :
                    //new record
                    $record = new Attendance();

                    $record->id = Str::uuid();
                    $record->employee_id = $employee->id;
                    $record->type_id = $option->id;
                    $record->time_in = $time;

                    $record->save();

                    $data = [
                        'message' => 'record created'
                    ];

                    return response()->json($data, 201);
                else :
                    $record->time_out = $time;
                    $record->save();

                    $data = [
                        'message' => 'record updated'
                    ];

                    return response()->json($data, 200);
                endif;
            else :
                $data = [
                    'message' => 'employee doesn\'t exist'
                ];

                return response()->json($data, 400);
            endif;
        endif;
    }
}
