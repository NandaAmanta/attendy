<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = config('module-permissions');
        foreach ($data as $module => $permissions) {
            foreach ($permissions as $permission) {
                \App\Models\Permission::firstOrCreate([
                    'action' => $permission,
                    'module' => $module,
                ]);
            }
        }
    }
}
