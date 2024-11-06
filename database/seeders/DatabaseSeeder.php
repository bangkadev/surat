<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin KBIHU Al Azhar',
            'email' => 'kbihu@al-azhar.or.id',
            'password' => Hash::make('H4j12025')
        ]);
    }
}
