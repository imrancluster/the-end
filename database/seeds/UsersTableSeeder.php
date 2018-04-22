<?php

use App\Living;
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

         //DB::table('users_activations')->insert([
         //   'user_id' => $adminUser->id, 'token' => str_random(30)
         //]);




        $role = new Role();
        $role->name = "Admin";
        $role->save();

        $permission = new Permission();
        $permission->name = "Administer roles & permissions";
        $permission->save();

        $role->givePermissionTo($permission);

        $adminUser->assignRole($role); //Assigning role to user


        // Member User
        $memberUser = User::create([
            'name'     => 'Imran1',
            'email'    => 'imran1@gmail.com',
            'password' => bcrypt('imran'),
            'is_admin' => true,
            'is_activated' => true
        ]);

        Living::create(['user_id' => $memberUser->id]);

        $role1 = new Role();
        $role1->name = "Member";
        $role1->save();

        $permission1 = new Permission();
        $permission1->name = "Create Note";
        $permission1->save();

        $role1->givePermissionTo($permission1);

        $permission2 = new Permission();
        $permission2->name = "Edit Note";
        $permission2->save();
        $role1->givePermissionTo($permission2);

        $permission3 = new Permission();
        $permission3->name = "Delete Note";
        $permission3->save();
        $role1->givePermissionTo($permission3);

        $memberUser->assignRole($role1); //Assigning role to user

        $factoryUsers = factory(User::class, 3)->create();
        foreach($factoryUsers as $user) {
            Living::create(['user_id' => $user->id]);
            $user->assignRole($role1); //Assigning role to user
        }
    }
}
