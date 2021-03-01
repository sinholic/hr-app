<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuids;

class Candidate extends Model
{
    use Uuids, SoftDeletes;
    protected $guarded = [];
    
    public function candidate_status()
    {
        return $this->belongsTo(Option::class, 'candidate_status_id', 'id')->withDefault([
            'type' => 'CANDIDATE_STATUS',
        ]);
    }

    public function recruitment()
    {
        return $this->belongsTo(Recruitment::class, 'recruitment_id', 'id');
    }
}
