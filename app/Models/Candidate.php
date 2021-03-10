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

    /**
     * Get all of the candidate_status_logs for the Candidate
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function candidate_status_logs()
    {
        return $this->hasMany(CandidateStatusLog::class, 'candidate_id', 'id');
    }

    public function recruitment()
    {
        return $this->belongsTo(Recruitment::class, 'recruitment_id', 'id');
    }
}
