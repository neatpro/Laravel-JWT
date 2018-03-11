<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'username'          => "Admin",
            'password'          => bcrypt('Admin'),
            'email'             => "admin@email.com",
            'first_name'        => "John",
            'last_name'         => "Doe",
            'remember_token'    => false,
        ]);
    }
}
