<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            [
                'name' => 'Senad',
                'email' => 'senad.okt97@gmail.com',
                'password' => 'password'
            ]
        );
    }
}
