<?php

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder {
    public function run() {
        $users = [
            [
                'id' => Str::uuid(),
                'name' => 'Admin',
                'email' => 'admin@mail.com',
                'password' => Hash::make('admin123'),
            ],
            [
                'id' => Str::uuid(),
                'name' => 'John',
                'email' => 'john@mail.com',
                'password' => Hash::make('John123'),
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Jane',
                'email' => 'jane@mail.com',
                'password' => Hash::make('Jane123'),
            ],
        ];

        foreach ($users as $user) {
            User::firstOrCreate(['email' => $user['email']], $user);
        }
    }
}
