<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Role::create(['name' => 'admin', 'level' => 0]);
        // $user = User::create([
        //     'name' => 'admin',
        //     'username' => 'admin',
        //     'status' => 'active',
        //     'email' => 'admin@gmail.com',
        //     'password' => bcrypt('password'),
        // ]);

         $user = User::create([
            'name' => 'admin',
            'username' => 'admin',
            'status' => 'active',
            'email' => 'jbson007@gmail.com',
            'password' => bcrypt('password'),
        ]);

        // assign single role
        $role = Role::findOrFail(1);
        $user->syncRoles([$role->name]);
    }
}
