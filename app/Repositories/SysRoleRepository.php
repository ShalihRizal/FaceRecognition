<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use App\Implementations\QueryBuilderImplementation;

class SysRoleRepository extends QueryBuilderImplementation
{

    public $fillable = ['role_id', 'task_id', 'group_id', 'created_at', 'created_by', 'updated_at', 'updated_by'];

    public function __construct()
    {
        $this->table = 'sys_roles';
        $this->pk = 'role_id';
    }

    public function getByModuleTask($module, $task, $group)
    {
        try {
            return DB::connection($this->db)
                ->table($this->table)
                ->join('sys_tasks', 'sys_roles.task_id', '=', 'sys_tasks.task_id')
                ->join('sys_modules', 'sys_tasks.module_id', '=', 'sys_modules.module_id')
                ->leftJoin('task_data', 'task_data.task_data_id', '=', 'sys_tasks.task_data_id')
                ->where('module_name', '=', $module)
                ->where('task_data_name', '=', $task)
                ->where('group_id', '=', $group)
                ->first();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

}
