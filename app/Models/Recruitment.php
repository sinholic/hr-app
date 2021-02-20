<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuids;

class Recruitment extends Model
{
    use Uuids, SoftDeletes;
    protected $guarded = [];

    public function department()
    {
        return $this->belongsTo(Option::class, 'department_id', 'id');
    }
    
    public function user_requested()
    {
        return $this->belongsTo(User::class, 'requested_by_user', 'id');
    }

    public function user_change_status()
    {
        return $this->belongsTo(User::class, 'change_request_status_by_user', 'id');
    }

    public function user_processed()
    {
        return $this->belongsTo(User::class, 'processed_by_user', 'id');
    }

    public function priority()
    {
        return $this->belongsTo(Option::class, 'priority_id', 'id');
    }

    public function request_status()
    {
        return $this->belongsTo(Option::class, 'request_status_id', 'id');
    }

    public function process_status()
    {
        return $this->belongsTo(Option::class, 'process_status_id', 'id');
    }

    public function candidates()
    {
        return $this->hasMany(Candidate::class, 'recruitment_id', 'id');
    }
}
