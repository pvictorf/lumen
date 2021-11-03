<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach(['admin', 'user', 'audit'] as $name) {
            Role::create([
                "name" => $name
            ])->each(function($role) {
                $role->givePermissionTo('read_question');
            });
        }
    }
}
