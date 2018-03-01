<?php

use App\User;
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
        User::truncate();

        User::create([
            'name'     => 'Imran Sarder',
            'email'    => 'imrancluster@gmail.com',
            'password' => bcrypt('imran'),
            'is_admin' => true
        ]);
    }
}
