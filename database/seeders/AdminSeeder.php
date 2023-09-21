<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Admin::where('email', config('app.admin.email'))->where('phone', config('app.admin.phone'))->doesntExist()) {
            Admin::insert([
                'name' => config('app.admin.name'),
                'email' => config('app.admin.email'),
                'phone' => config('app.admin.phone'),
                'password' => Hash::make(config('app.admin.password'))
            ]);
        }
    }
}
