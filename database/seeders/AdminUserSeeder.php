<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;    

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::UpdateOrCreate(
        ['email'=>'admin@dinkes.local'],
        ['name'=>'Admin Dinkes', 'password'=>Hash::make('password123')]);
    }
}
