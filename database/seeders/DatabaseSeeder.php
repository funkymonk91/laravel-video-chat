<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::factory()->createMany([
            [
                'name' => 'Sean',
                'email' => 's.frankruyter@gmail.com',
                'password' => bcrypt('password')
            ],
            [
                'name' => 'Test',
                'email' => 'test@test.com',
                'password' => bcrypt('password')
            ],
        ]);
    }
}
