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
        $admin_dosent_exists = Admin::where('email', config('app.admin.email'))
            ->where('phone', config('app.admin.phone'))
            ->doesntExist();

        if ($$admin_dosent_exists) {
            $admin = new Admin();
            $admin->name = config('app.admin.name');
            $admin->email = config('app.admin.email');
            $admin->phone = config('app.admin.phone');
            $admin->password = Hash::make(config('app.admin.password'));
            $admin->generateAdminProfile();
            $admin->save();
        }
    }
}
