<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role as SpatieRole;
use App\Traits\Uuids;

class Role extends SpatieRole
{
    use Uuids;
}
