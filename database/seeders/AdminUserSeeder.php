<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate([
            'email' => 'admin@step-opsi.id',
        ], [
            'name' => 'Admin STEP',
            'password' => Hash::make('StepAdmin2026!'),
            'email_verified_at' => now(),
        ]);

        if (method_exists($admin, 'assignRole')) {
            $admin->assignRole('admin');
        }

        $researcher = User::firstOrCreate([
            'email' => 'peneliti@step-opsi.id',
        ], [
            'name' => 'Peneliti OPSI',
            'password' => Hash::make('StepPeneliti2026!'),
            'email_verified_at' => now(),
        ]);

        if (method_exists($researcher, 'assignRole')) {
            $researcher->assignRole('researcher');
        }
    }
}
