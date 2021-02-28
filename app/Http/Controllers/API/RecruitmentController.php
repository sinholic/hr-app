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
        $recruitments = Recruitment::with(
            'department',
            'user_requested',
            'user_change_status',
            'user_processed',
            'priority',
            'request_status',
            'process_status',
            'candidates',
            'candidates.candidate_status'
        )
        ->whereHas('process_status', function($query_process){
            return $query_process->whereNotIn('name', [
                'DONE',
                'REJECTED'
            ]);
        })
        ->whereHas('candidates.candidate_status', function($query_process){
            return $query_process->whereNotIn('name', [
                'NOT SUITABLE TO INTERVIEW',
                'CV NOT SUITABLE',
                'NOT SUITABLE FOR OL',
                'NOT SUITABLE'
            ]);
        })
        ->get();

        return response()->json($recruitments, 200);
    }
}
