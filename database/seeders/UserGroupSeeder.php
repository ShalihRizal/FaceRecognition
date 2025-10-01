<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('sys_user_groups')->insert([
            [
                'group_name'     => 'Super Admin'
            ],

            [
                'group_name'     => 'Admin'
            ],

            [
                'group_name'     => 'User'
            ],
        ]);
    }
}
