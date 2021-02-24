<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuids;

class Candidate extends Model
{
    use Uuids, SoftDeletes;
    protected $guarded = [];

    protected $casts = [
        'joindate'  => 'date:Y-m-d',
        'interview_date' => 'datetime:Y-m-d H:i',
    ];

    public function candidate_status()
    {
        return $this->belongsTo('App\Models\Option', 'candidate_status_id', 'id')->withDefault([
            'type' => 'CANDIDATE_STATUS',
        ]);
    }

    public function recruitment()
    {
        return $this->belongsTo('App\Models\Recruitment', 'recruitment_id', 'id');
    }
}
