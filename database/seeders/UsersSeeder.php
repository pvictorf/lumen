<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User();
        $user->name = "Adminer";
        $user->email = "admin@hotmail.com";
        $user->password = "secret"; // Automaticly hashed by model
        $user->cpf = "099118165";
        $user->assignRole('admin');
        $user->save();

        User::factory()->count(4)->create()->each(function ($user) {
            $user->assignRole('user'); 
        });
    }
}
