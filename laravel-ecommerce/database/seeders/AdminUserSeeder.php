<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles if they don't exist
        $superAdminRole = Role::firstOrCreate(['name' => 'super-admin']);
        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        // Create Super Admin user
        $superAdmin = User::firstOrCreate(
            ['email' => 'admin@demo.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('Admin@123'),
                'email_verified_at' => now(),
            ]
        );
        $superAdmin->assignRole($superAdminRole);

        // Create regular Admin user
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('Admin@123'),
                'email_verified_at' => now(),
            ]
        );
        $admin->assignRole($adminRole);

        $this->command->info('Admin users created successfully!');
        $this->command->info('');
        $this->command->info('Admin Credentials:');
        $this->command->info('==================');
        $this->command->info('Super Admin:');
        $this->command->info('Email: admin@demo.com');
        $this->command->info('Password: Admin@123');
        $this->command->info('');
        $this->command->info('Regular Admin:');
        $this->command->info('Email: admin@example.com');
        $this->command->info('Password: Admin@123');
        $this->command->info('==================');
    }
}