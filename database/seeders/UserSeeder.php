<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::query()->updateOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Administrator SISFREK',
                'email' => 'admin@simfrek.test',
                'password' => 'admin123',
                'is_admin' => true,
            ]
        );
    }
}
