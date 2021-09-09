<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Permission;

class GeneratePermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permission:generate-route';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate permissions from routes name';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $routeCollections   = \Route::getRoutes();
        $excludedRoutes     = array(
            '',
            'index',
            'login',
            'logout',
            'register',
            'index',
            'password.request',
            'password.email',
            'password.reset',
            'password.update',
            'password.confirm',
            'recruitments.index',
            'candidates.index',
            'employees.index',
            'users.index',
            'departments.index',
            'candidate_status.index',
            'roles.index',
            'permissions.index'
        );

        foreach ($routeCollections as $value) {
            if (! in_array($value->getName(),$excludedRoutes)) {
                $this->info($value->getName());
                Permission::firstOrCreate([
                    'name' => $value->getName()
                ]);                
            }
        }
    }
}
