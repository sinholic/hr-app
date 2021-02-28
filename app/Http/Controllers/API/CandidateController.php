<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Candidate;
use App\Models\Recruitment;
use App\Models\Option;
use App\Models\Log as LogDB;
use Ramsey\Uuid\Uuid;

class CandidateController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function processed()
    {
        $candidates = Candidate::whereHas('candidate_status', function($query_process){
            return $query_process->whereNotIn('name', [
                'NOT SUITABLE TO INTERVIEW',
                'CV NOT SUITABLE',
                'NOT SUITABLE FOR OL',
                'NOT SUITABLE'
            ]);
        })->get();

        return response()->json($candidates, 200);
    }

}