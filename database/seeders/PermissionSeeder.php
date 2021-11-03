<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach(['create_question', 'read_question', 'edit_question', 'del_question'] as $name) {
            Permission::create([
                "name" => $name
            ]);
        }
    }
}
