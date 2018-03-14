<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // User::truncate();

        $adminUser = User::create([
            'name'     => 'Imran Sarder',
            'email'    => 'imrancluster@gmail.com',
            'password' => bcrypt('imran'),
            'is_admin' => true,
            'is_activated' => true
        ]);

        // DB::table('users_activations')->insert([
        //    'user_id' => $adminUser->id, 'token' => str_random(30)
        // ]);




        $role = new Role();
        $role->name = "Admin";
        $role->save();

        $permission = new Permission();
        $permission->name = "Administer roles & permissions";
        $permission->save();

        $role->givePermissionTo($permission);

        $adminUser->assignRole($role); //Assigning role to user


        //factory(User::class, 50)->create();
    }
}
