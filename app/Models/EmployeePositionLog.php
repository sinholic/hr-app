<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuids;

class EmployeePositionLog extends Model
{
    use Uuids, SoftDeletes;
    protected $guarded = [];
}
