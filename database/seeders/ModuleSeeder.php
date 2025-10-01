<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModuleSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		DB::table('sys_modules')->insert([
			[
				'module_name' 		=> 'Dashboard',
				'created_by'		=> '1',
				'created_at'		=> date('Y-m-d H:i:s')
			],
			[
				'module_name' 		=> 'SysModule',
				'created_by'		=> '1',
				'created_at'		=> date('Y-m-d H:i:s')
			],
			[
				'module_name' 		=> 'SysTask',
				'created_by'		=> '1',
				'created_at'		=> date('Y-m-d H:i:s')
			],
			[
				'module_name' 		=> 'SysRole',
				'created_by'		=> '1',
				'created_at'		=> date('Y-m-d H:i:s')
			],
			[
				'module_name' 		=> 'SysMenu',
				'created_by'		=> '1',
				'created_at'		=> date('Y-m-d H:i:s')
			],
			[
				'module_name' 		=> 'Users',
				'created_by'		=> '1',
				'created_at'		=> date('Y-m-d H:i:s')
			],
			[
				'module_name' 		=> 'UserGroup',
				'created_by'		=> '1',
				'created_at'		=> date('Y-m-d H:i:s')
			],

			[
				'module_name' 		=> 'LogActivity',
				'created_by'		=> '1',
				'created_at'		=> date('Y-m-d H:i:s')
			],
			[
				'module_name' 		=> 'Configuration',
				'created_by'		=> '1',
				'created_at'		=> date('Y-m-d H:i:s')
			],
		]);
	}
}
