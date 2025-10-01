<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Factories\TaskDataFactory;
use Modules\SysTask\Repositories\TaskDataRepository;
use Illuminate\Support\Facades\DB;

class TaskDataSeeder extends Seeder
{
    public function run()
	{
		DB::table('task_data')->insert([
			[
			    'task_data_id' 		=> '1', 
				'task_data_name'    => 'index',
				'created_by'		=> '1',
				'created_at'		=> date('Y-m-d H:i:s')
			],
			[
			    'task_data_id' 		=> '2', 
				'task_data_name'    => 'create',
				'created_by'		=> '1',
				'created_at'		=> date('Y-m-d H:i:s')
            ],
            [
			    'task_data_id' 		=> '3', 
				'task_data_name'    => 'edit',
				'created_by'		=> '1',
				'created_at'		=> date('Y-m-d H:i:s')
            ],
            [
			    'task_data_id' 		=> '4', 
				'task_data_name'    => 'destroy',
				'created_by'		=> '1',
				'created_at'		=> date('Y-m-d H:i:s')
			],
			[
			    'task_data_id' 		=> '5', 
				'task_data_name'    => 'store',
				'created_by'		=> '1',
				'created_at'		=> date('Y-m-d H:i:s')
			],
			[
			    'task_data_id' 		=> '6', 
				'task_data_name'    => 'update',
				'created_by'		=> '1',
				'created_at'		=> date('Y-m-d H:i:s')
			],
			[
			    'task_data_id' 		=> '7', 
				'task_data_name'    => 'show',
				'created_by'		=> '1',
				'created_at'		=> date('Y-m-d H:i:s')
            ],
        ]);
    }
}
