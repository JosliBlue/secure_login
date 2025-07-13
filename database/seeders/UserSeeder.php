<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Crypt;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'email' => Crypt::encryptString('eliassaulojoslimuyu@gmail.com'),
            'password' => Crypt::encryptString('Laclave@PRO.com415'),
        ]);
    }
}
