<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuids;

class Option extends Model
{
    use Uuids, SoftDeletes;
    protected $guarded = [];

    /**
     * Get all of the users for the Option
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()
    {
        return $this->hasMany(User::class, 'department_id', 'id');
    }

    /**
     * Get all of the recruitment_departments for the Option
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function recruitment_departments()
    {
        return $this->hasMany(Recruitment::class, 'department_id', 'id');
    }

    /**
     * Get all of the recruitment_priorities for the Option
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function recruitment_priorities()
    {
        return $this->hasMany(Recruitment::class, 'priority_id', 'id');
    }

    /**
     * Get all of the recruitment_request_statuses for the Option
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function recruitment_request_statuses()
    {
        return $this->hasMany(Recruitment::class, 'request_status_id', 'id');
    }

    /**
     * Get all of the recruitment_process_statuses for the Option
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function recruitment_process_statuses()
    {
        return $this->hasMany(Recruitment::class, 'process_status_id', 'id');
    }

    /**
     * Get all of the candidate_statuses for the Option
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function candidate_statuses()
    {
        return $this->hasMany(Candidate::class, 'process_status_id', 'id');
    }
}
