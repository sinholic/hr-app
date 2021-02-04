<?php

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            'Super Admin',
            'Management',
            'Manager',
            'HR Manager',
            'Team Lead',
            'Employee'
        ];
        $users = User::all();

        foreach ($roles as $key => $role) {
            Role::create([
                'name' => $role
            ]);
            $users[$key]->assignRole($role);
        }
    }
}
