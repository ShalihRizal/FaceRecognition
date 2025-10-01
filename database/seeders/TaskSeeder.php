<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TaskSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		DB::table('sys_tasks')->insert([
			[
			    'module_id' 		=> '1', //Dashboard
				'task_data_id'			=> '1',
				'created_by'		=> '1',
				'created_at'		=> date('Y-m-d H:i:s')
			],
			[
			    'module_id' 		=> '2', //SysModule
				'task_data_id'			=> '1',
				'created_by'		=> '1',
				'created_at'		=> date('Y-m-d H:i:s')
			],
			[
			    'module_id' 		=> '2', //SysModule
				'task_data_id'			=> '2',
				'created_by'		=> '1',
				'created_at'		=> date('Y-m-d H:i:s')
			],
			[
			    'module_id' 		=> '2', //SysModule
				'task_data_id'			=> '3',
				'created_by'		=> '1',
				'created_at'		=> date('Y-m-d H:i:s')
			],
			[
			    'module_id' 		=> '2', //SysModule
				'task_data_id'			=> '4',
				'created_by'		=> '1',
				'created_at'		=> date('Y-m-d H:i:s')
			],
			[
			    'module_id' 		=> '2', //SysModule
				'task_data_id'			=> '5',
				'created_by'		=> '1',
				'created_at'		=> date('Y-m-d H:i:s')
			],
			[
			    'module_id' 		=> '2', //SysModule
				'task_data_id'			=> '6',
				'created_by'		=> '1',
				'created_at'		=> date('Y-m-d H:i:s')
			],
			[
			    'module_id' 		=> '2', //SysModule
				'task_data_id'			=> '7',
				'created_by'		=> '1',
				'created_at'		=> date('Y-m-d H:i:s')
			],
			[

			    'module_id' 		=> '3', //SysTask
				'task_data_id'			=> '1',
				'created_by'		=> '1',
				'created_at'		=> date('Y-m-d H:i:s')
			],
			[
			    'module_id' 		=> '3', //SysTask
				'task_data_id'			=> '2',
				'created_by'		=> '1',
				'created_at'		=> date('Y-m-d H:i:s')
			],
			[
			    'module_id' 		=> '3', //SysTask
				'task_data_id'			=> '3',
				'created_by'		=> '1',
				'created_at'		=> date('Y-m-d H:i:s')
			],
			[
			    'module_id' 		=> '3', //SysTask
				'task_data_id'			=> '4',
				'created_by'		=> '1',
				'created_at'		=> date('Y-m-d H:i:s')
			],
			[
			    'module_id' 		=> '3', //SysTask
				'task_data_id'			=> '5',
				'created_by'		=> '1',
				'created_at'		=> date('Y-m-d H:i:s')
			],
			[
			    'module_id' 		=> '3', //SysTask
				'task_data_id'			=> '6',
				'created_by'		=> '1',
				'created_at'		=> date('Y-m-d H:i:s')
			],
			[
			    'module_id' 		=> '3', //SysTask
				'task_data_id'			=> '7',
				'created_by'		=> '1',
				'created_at'		=> date('Y-m-d H:i:s')
			],
			[

				'module_id' 		=> '4', //SysRole
				'task_data_id'			=> '1',
				'created_by'		=> '1',
				'created_at'		=> date('Y-m-d H:i:s')
			],
			[
				'module_id' 		=> '4', //SysRole
				'task_data_id'			=> '2',
				'created_by'		=> '1',
				'created_at'		=> date('Y-m-d H:i:s')
			],
			[
				'module_id' 		=> '4', //SysRole
				'task_data_id'			=> '3',
				'created_by'		=> '1',
				'created_at'		=> date('Y-m-d H:i:s')
			],
			[
				'module_id' 		=> '4', //SysRole
				'task_data_id'			=> '4',
				'created_by'		=> '1',
				'created_at'		=> date('Y-m-d H:i:s')
			],
			[
				'module_id' 		=> '4', //SysRole
				'task_data_id'			=> '5',
				'created_by'		=> '1',
				'created_at'		=> date('Y-m-d H:i:s')
			],
			[
				'module_id' 		=> '4', //SysRole
				'task_data_id'			=> '6',
				'created_by'		=> '1',
				'created_at'		=> date('Y-m-d H:i:s')
			],
			[
				'module_id' 		=> '4', //SysRole
				'task_data_id'			=> '7',
				'created_by'		=> '1',
				'created_at'		=> date('Y-m-d H:i:s')
			],
			[

				'module_id' 		=> '5', //SysMenu
				'task_data_id'			=> '1',
				'created_by'		=> '1',
				'created_at'		=> date('Y-m-d H:i:s')
			],
			[
				'module_id' 		=> '5', //SysMenu
				'task_data_id'			=> '2',
				'created_by'		=> '1',
				'created_at'		=> date('Y-m-d H:i:s')
			],
			[
				'module_id' 		=> '5', //SysMenu
				'task_data_id'			=> '3',
				'created_by'		=> '1',
				'created_at'		=> date('Y-m-d H:i:s')
			],
			[
				'module_id' 		=> '5', //SysMenu
				'task_data_id'			=> '4',
				'created_by'		=> '1',
				'created_at'		=> date('Y-m-d H:i:s')
			],
			[
				'module_id' 		=> '5', //SysMenu
				'task_data_id'			=> '5',
				'created_by'		=> '1',
				'created_at'		=> date('Y-m-d H:i:s')
			],
			[
				'module_id' 		=> '5', //SysMenu
				'task_data_id'			=> '6',
				'created_by'		=> '1',
				'created_at'		=> date('Y-m-d H:i:s')
			],
			[
				'module_id' 		=> '5', //SysMenu
				'task_data_id'			=> '7',
				'created_by'		=> '1',
				'created_at'		=> date('Y-m-d H:i:s')
			],
			[

				'module_id' 		=> '6', //Users
				'task_data_id'			=> '1',
				'created_by'		=> '1',
				'created_at'		=> date('Y-m-d H:i:s')
			],
			[
				'module_id' 		=> '6', //Users
				'task_data_id'			=> '2',
				'created_by'		=> '1',
				'created_at'		=> date('Y-m-d H:i:s')
			],
			[
				'module_id' 		=> '6', //Users
				'task_data_id'			=> '3',
				'created_by'		=> '1',
				'created_at'		=> date('Y-m-d H:i:s')
			],
			[
				'module_id' 		=> '6', //Users
				'task_data_id'			=> '4',
				'created_by'		=> '1',
				'created_at'		=> date('Y-m-d H:i:s')
			],
			[
				'module_id' 		=> '6', //Users
				'task_data_id'			=> '5',
				'created_by'		=> '1',
				'created_at'		=> date('Y-m-d H:i:s')
			],
			[
				'module_id' 		=> '6', //Users
				'task_data_id'			=> '6',
				'created_by'		=> '1',
				'created_at'		=> date('Y-m-d H:i:s')
			],
			[
				'module_id' 		=> '6', //Users
				'task_data_id'			=> '7',
				'created_by'		=> '1',
				'created_at'		=> date('Y-m-d H:i:s')
			],
			[

				'module_id' 		=> '7', //UserGroup
				'task_data_id'			=> '1',
				'created_by'		=> '1',
				'created_at'		=> date('Y-m-d H:i:s')
			],
			[
				'module_id' 		=> '7', //UserGroup
				'task_data_id'			=> '2',
				'created_by'		=> '1',
				'created_at'		=> date('Y-m-d H:i:s')
			],
			[
				'module_id' 		=> '7', //UserGroup
				'task_data_id'			=> '3',
				'created_by'		=> '1',
				'created_at'		=> date('Y-m-d H:i:s')
			],
			[
				'module_id' 		=> '7', //UserGroup
				'task_data_id'			=> '4',
				'created_by'		=> '1',
				'created_at'		=> date('Y-m-d H:i:s')
			],
			[
				'module_id' 		=> '7', //UserGroup
				'task_data_id'			=> '5',
				'created_by'		=> '1',
				'created_at'		=> date('Y-m-d H:i:s')
			],
			[
				'module_id' 		=> '7', //UserGroup
				'task_data_id'			=> '6',
				'created_by'		=> '1',
				'created_at'		=> date('Y-m-d H:i:s')
			],
			[
				'module_id' 		=> '7', //UserGroup
				'task_data_id'			=> '7',
				'created_by'		=> '1',
				'created_at'		=> date('Y-m-d H:i:s')
			],
			[
				'module_id' 		=> '8', //LogActivity
				'task_data_id'			=> '1',
				'created_by'		=> '1',
				'created_at'		=> date('Y-m-d H:i:s')
			],
			[
				'module_id' 		=> '8', //LogActivity
				'task_data_id'			=> '2',
				'created_by'		=> '1',
				'created_at'		=> date('Y-m-d H:i:s')
			],
			[
				'module_id' 		=> '9', //LogActivity
				'task_data_id'			=> '3',
				'created_by'		=> '1',
				'created_at'		=> date('Y-m-d H:i:s')
			],
			[
				'module_id' 		=> '8', //LogActivity
				'task_data_id'			=> '4',
				'created_by'		=> '1',
				'created_at'		=> date('Y-m-d H:i:s')
			],
			[
				'module_id' 		=> '8', //LogActivity
				'task_data_id'			=> '5',
				'created_by'		=> '1',
				'created_at'		=> date('Y-m-d H:i:s')
			],
			[
				'module_id' 		=> '8', //LogActivity
				'task_data_id'			=> '6',
				'created_by'		=> '1',
				'created_at'		=> date('Y-m-d H:i:s')
			],
			[
				'module_id' 		=> '8', //LogActivity
				'task_data_id'			=> '7',
				'created_by'		=> '1',
				'created_at'		=> date('Y-m-d H:i:s')
			],
			[
				'module_id' 		=> '9', //Configuration
				'task_data_id'			=> '1',
				'created_by'		=> '1',
				'created_at'		=> date('Y-m-d H:i:s')
			],
			[
				'module_id' 		=> '9', //Configuration
				'task_data_id'			=> '2',
				'created_by'		=> '1',
				'created_at'		=> date('Y-m-d H:i:s')
			],
			[
				'module_id' 		=> '9', //Configuration
				'task_data_id'			=> '3',
				'created_by'		=> '1',
				'created_at'		=> date('Y-m-d H:i:s')
			],
			[
				'module_id' 		=> '9', //Configuration
				'task_data_id'			=> '4',
				'created_by'		=> '1',
				'created_at'		=> date('Y-m-d H:i:s')
			],
			[
				'module_id' 		=> '9', //Configuration
				'task_data_id'			=> '5',
				'created_by'		=> '1',
				'created_at'		=> date('Y-m-d H:i:s')
			],
			[
				'module_id' 		=> '9', //Configuration
				'task_data_id'			=> '6',
				'created_by'		=> '1',
				'created_at'		=> date('Y-m-d H:i:s')
			],
			[
				'module_id' 		=> '9', //Configuration
				'task_data_id'			=> '7',
				'created_by'		=> '1',
				'created_at'		=> date('Y-m-d H:i:s')
			],
		]);
	}
}
