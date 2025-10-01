<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('sys_users')->insert(
            [
                [
                    'user_image'         => NULL,
                    'user_name'         => 'Super Admin',
                    'user_username'      => 'superadmin',
                    'user_email'         => 'superadmin@gmail.com',
                    'user_password'     => Hash::make('12345678'),
                    'group_id'            => '1',
                    'created_at'        => date('Y-m-d H:i:s'),
                    'user_status'       => '1'
                ],

                [
                    'user_image'         => NULL,
                    'user_name'         => 'Admin',
                    'user_username'      => 'admin',
                    'user_email'         => 'admin@gmail.com',
                    'user_password'     => Hash::make('12345678'),
                    'group_id'            => '2',
                    'created_at'        => date('Y-m-d H:i:s'),
                    'user_status'       => '1'
                ],

                [
                    'user_image'         => NULL,
                    'user_name'         => 'user',
                    'user_username'      => 'user',
                    'user_email'         => 'user@gmail.com',
                    'user_password'     => Hash::make('12345678'),
                    'group_id'            => '3',
                    'created_at'        => date('Y-m-d H:i:s'),
                    'user_status'       => '1'
                ],
            ]
        );
    }
}
