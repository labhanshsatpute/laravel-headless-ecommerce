<?php

namespace Database\Seeders;

use App\Enums\Permission as EnumsPermission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (EnumsPermission::cases() as $permission) {
            Permission::create([
                'name' => $permission->value,
                'guard_name' => 'admin'
            ]);
        }
        
    }
}
