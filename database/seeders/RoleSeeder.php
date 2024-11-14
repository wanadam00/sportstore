<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure the role exists
        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        // Assign 'admin' role to a specific user
        $user = User::find(1); // Replace with the actual user ID
        if ($user) {
            $user->assignRole($adminRole);
        }
    }
}
