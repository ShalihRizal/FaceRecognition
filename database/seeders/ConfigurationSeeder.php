<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConfigurationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('configurations')->insert([
            [
                'key'               => 'base_url',
                'value'             => 'https://collabs.nocturnailed.tech/',
                'created_by'        => '1',
                'created_at'        => date('Y-m-d H:i:s')
            ],
            [
                'key'                 => 'image_path',
                'value'             => 'storage/uploads/images/',
                'created_by'        => '1',
                'created_at'        => date('Y-m-d H:i:s')
            ],
            [
                'key'                 => 'file_path',
                'value'             => 'storage/uploads/files/',
                'created_by'        => '1',
                'created_at'        => date('Y-m-d H:i:s')
            ],

            [
                'key'                 => 'url_profile',
                'value'             => 'img/avatars/profile.jpg',
                'created_by'        => '1',
                'created_at'        => date('Y-m-d H:i:s')
            ],

            [
                'key'                 => 'access_token',
                'value'             => 'collabs',
                'created_by'        => '1',
                'created_at'        => date('Y-m-d H:i:s')
            ],
        ]);
    }
}
