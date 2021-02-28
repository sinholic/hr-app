<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Recruitment;
use App\Models\Option;
use App\Models\Log as LogDB;

class RecruitmentController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function open()
    {
        $recruitments = Recruitment::whereHas('process_status', function($query_process){
            return $query_process->whereNotIn('name', [
                'DONE',
                'REJECTED'
            ]);
        })->get();

        return response()->json($recruitments, 200);
    }
}
