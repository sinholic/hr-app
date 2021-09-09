<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Recruitment;
use App\Models\Option;
use App\Models\Log as LogDB;
use App\Models\Employee;
use Illuminate\Support\Carbon;
use Validator;
use Str;

class EmployeeController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function registerEmployee(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'name' => 'required',
            'phone_number' => 'required',
        ]);

        $email = $request->email;
        $name = $request->name;
        $phone_number = $request->phone_number;

        if ($validator->fails()) :
            $messages = $validator->errors()->all();
            $msg = $messages[0];

            $data = ['response_message' => $msg];
            return response()->json($data, 400);
        else :
            $employee = new Employee();

            $employee->id = Str::uuid();
            $employee->name = $name;
            $employee->email = $email;
            $employee->phone = $phone_number;

            $employee->save();

            $data = [
                'message' => 'record created'
            ];

            return response()->json($data, 201);

        endif;
    }
}
