<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use App\Models\AdminGroup;

class CreateAdminsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = [
            [
                'name' => 'admin',
                'admin_group_id' => 1,
                'email' => 'admin@smarterspanel.com',
                'password' => bcrypt('password'),
                'status' => 1,
            ]
        ];

        foreach ($user as $key => $value) {
            Admin::create($value);
        }
        AdminGroup::create([
            'name' => 'Full Admin',
            // 'status' => 1,
        ]);
    }
}
