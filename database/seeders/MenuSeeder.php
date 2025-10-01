<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		DB::table('sys_menus')->insert([
			[
				'menu_name' 		=> 'Beranda',
				'module_id' 		=> '1',
				'menu_url' 			=> 'beranda',
				'menu_icon' 		=> 'home',
				'menu_is_sub' 		=> '0',
				'menu_parent_id' 	=> NULL,
				'menu_position' 	=> '1',
				'created_by'		=> '1',
				'created_at'		=> date('Y-m-d H:i:s')
			],
			[
				'menu_name' 		=> 'Hak Akses',
				'module_id' 		=> NULL,
				'menu_url' 			=> 'javascript:void(0)',
				'menu_icon' 		=> 'settings',
				'menu_is_sub' 		=> '0',
				'menu_parent_id' 	=> NULL,
				'menu_position' 	=> '2',
				'created_by'		=> '1',
				'created_at'		=> date('Y-m-d H:i:s')
			],
			[
				'menu_name' 		=> 'Module',
				'module_id' 		=> '2',
				'menu_url' 			=> 'sysmodule',
				'menu_icon' 		=> '',
				'menu_is_sub' 		=> '1',
				'menu_parent_id' 	=> '2',
				'menu_position' 	=> '1',
				'created_by'		=> '1',
				'created_at'		=> date('Y-m-d H:i:s')
			],
			[
				'menu_name' 		=> 'Task',
				'module_id' 		=> '3',
				'menu_url' 			=> 'systask',
				'menu_icon' 		=> '',
				'menu_is_sub' 		=> '1',
				'menu_parent_id' 	=> '2',
				'menu_position' 	=> '2',
				'created_by'		=> '1',
				'created_at'		=> date('Y-m-d H:i:s')
			],
			[
				'menu_name' 		=> 'Role',
				'module_id' 		=> '4',
				'menu_url' 			=> 'sysrole',
				'menu_icon' 		=> '',
				'menu_is_sub' 		=> '1',
				'menu_parent_id' 	=> '2',
				'menu_position' 	=> '3',
				'created_by'		=> '1',
				'created_at'		=> date('Y-m-d H:i:s')
			],
			[
				'menu_name' 		=> 'Menu',
				'module_id' 		=> '5',
				'menu_url' 			=> 'sysmenu',
				'menu_icon' 		=> '',
				'menu_is_sub' 		=> '1',
				'menu_parent_id' 	=> '2',
				'menu_position' 	=> '4',
				'created_by'		=> '1',
				'created_at'		=> date('Y-m-d H:i:s')
			],
			[
				'menu_name' 		=> 'Pengguna',
				'module_id' 		=> NULL,
				'menu_url' 			=> 'javascript:void(0)',
				'menu_icon' 		=> 'users',
				'menu_is_sub' 		=> '0',
				'menu_parent_id' 	=> NULL,
				'menu_position' 	=> '3',
				'created_by'		=> '1',
				'created_at'		=> date('Y-m-d H:i:s')
			],
			[
				'menu_name' 		=> 'Daftar Pengguna',
				'module_id' 		=> '7',
				'menu_url' 			=> 'users',
				'menu_icon' 		=> '',
				'menu_is_sub' 		=> '1',
				'menu_parent_id' 	=> '7',
				'menu_position' 	=> '1',
				'created_by'		=> '1',
				'created_at'		=> date('Y-m-d H:i:s')
			],
			[
				'menu_name' 		=> 'Grup Pengguna',
				'module_id' 		=> '6',
				'menu_url' 			=> 'usergroup',
				'menu_icon' 		=> '',
				'menu_is_sub' 		=> '1',
				'menu_parent_id' 	=> '7',
				'menu_position' 	=> '2',
				'created_by'		=> '1',
				'created_at'		=> date('Y-m-d H:i:s')
			],

			[
				'menu_name' 		=> 'Pengaturan',
				'module_id' 		=> NULL,
				'menu_url' 			=> 'javascript:void(0)',
				'menu_icon' 		=> 'tool',
				'menu_is_sub' 		=> '0',
				'menu_parent_id' 	=> NULL,
				'menu_position' 	=> '4',
				'created_by'		=> '1',
				'created_at'		=> date('Y-m-d H:i:s')
			],


			[
				'menu_name' 		=> 'Log Aktivitas',
				'module_id' 		=> '8',
				'menu_url' 			=> 'logactivity',
				'menu_icon' 		=> 'activity',
				'menu_is_sub' 		=> '0',
				'menu_parent_id' 	=> NULL,
				'menu_position' 	=> '5',
				'created_by'		=> '1',
				'created_at'		=> date('Y-m-d H:i:s')
			],
			[
				'menu_name' 		=> 'Konfigurasi',
				'module_id' 		=> '9',
				'menu_url' 			=> 'configuration',
				'menu_icon' 		=> 'check-circle',
				'menu_is_sub' 		=> '0',
				'menu_parent_id' 	=> NULL,
				'menu_position' 	=> '6',
				'created_by'		=> '1',
				'created_at'		=> date('Y-m-d H:i:s')
			],
		]);
	}
}
