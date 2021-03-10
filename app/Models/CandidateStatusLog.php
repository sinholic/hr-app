<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuids;

class CandidateStatusLog extends Model
{
    use Uuids, SoftDeletes;
    protected $guarded = [];

    /**
     * Get the candidate that owns the CandidateStatusLog
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class, 'candidate_id', 'id');
    }
}
