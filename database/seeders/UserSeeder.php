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
            'name' => 'superadmin2',
            'username' => 'superadmin2',
            'status' => 'active',
            'email' => 'superadmin2@gmail.com',
            'password' => bcrypt('password'),
        ]);

        // assign single role
        $role = Role::findOrFail(3);
        $user->syncRoles([$role->name]);
    }
}
