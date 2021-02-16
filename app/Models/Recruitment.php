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
        return $this->belongsTo('App\Models\Option', 'department_id', 'id')->withDefault([
            'type' => 'DEPARTMENT',
        ]);
    }
    
    public function user_requested()
    {
        return $this->belongsTo('App\Models\User', 'requested_by_user', 'id');
    }

    public function user_change_status()
    {
        return $this->belongsTo('App\Models\User', 'change_request_status_by_user', 'id');
    }

    public function user_processed()
    {
        return $this->belongsTo('App\Models\User', 'processed_by_user', 'id');
    }

    public function priority()
    {
        return $this->belongsTo('App\Models\Option', 'priority_id', 'id')->withDefault([
            'type' => 'PRIORITY',
        ]);
    }

    public function request_status()
    {
        return $this->belongsTo('App\Models\Option', 'request_status_id', 'id')->withDefault([
            'type' => 'REQUEST_STATUS',
        ]);
    }

    public function process_status()
    {
        return $this->belongsTo('App\Models\Option', 'process_status_id', 'id')->withDefault([
            'type' => 'PROCESS_STATUS',
        ]);
    }

    public function candidates()
    {
        return $this->hasMany('App\Models\Candidate', 'recruitment_id', 'id');
    }
}
