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
        //Role::create(['name' => 'superadmin', 'level' => 0]);
        $user = User::create([
            'name' => 'superadmin',
            'username' => 'superadmin',
            'status' => 'active',
            'email' => 'superadmin@gmail.com',
            'password' => bcrypt('password'),
        ]);

        // assign single role
        $role = Role::findOrFail(1);
        $user->syncRoles([$role->name]);
    }
}
