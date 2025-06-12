<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Role::factory()
            ->count(1)
            ->create([
                'roles' => 'Программист'
            ]);

        Role::factory()
            ->count(1)
            ->create([
                'roles' => 'Менеджер'
            ]);

    }
}
