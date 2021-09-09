<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuids;

class Employee extends Model
{
    use Uuids, SoftDeletes;
    protected $guarded = [];

    /**
     * Get all of the position_logs for the Employee
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function position_logs()
    {
        return $this->hasMany(EmployeePositionLog::class, 'employee_id', 'id');
    }

    /**
     * Get all of the attendances for the Employee
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'employee_id', 'id');
    }

    /**
     * The teams that belong to the Employee
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function teams()
    {
        return $this->belongsToMany(Option::class, 'team_employee', 'team_id', 'employee_id')
        ->withPivot('position')
    	->withTimestamps();
    }
}
