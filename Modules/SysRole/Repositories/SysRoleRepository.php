<?php

namespace Modules\SysRole\Repositories;

use App\Implementations\QueryBuilderImplementation;
use Illuminate\Support\Facades\DB;

class SysRoleRepository extends QueryBuilderImplementation
{

    public $fillable = ['task_id', 'group_id', 'created_at', 'created_by', 'updated_at', 'updated_by'];

    public function __construct()
    {
        $this->table = 'sys_roles';
        $this->pk = 'role_id';
    }

    // public function getModuleTask()
    // {
    //     try {
    //         return DB::connection($this->db)
    //             ->table("sys_modules")
    //             ->select("sys_modules.module_name", DB::raw("GROUP_CONCAT(sys_tasks.task_name, ',') as task"), DB::raw("GROUP_CONCAT(sys_tasks.task_id, ',') as taskid"))
    //             ->leftJoin('sys_tasks', 'sys_tasks.module_id', '=', 'sys_modules.module_id')
    //             ->groupBy('sys_modules.module_id')
    //             ->orderBy('module_name')
    //             ->get();
    //     } catch (Exception $e) {
    //         return $e->getMessage();
    //     }
    // }

    public function getModuleTask()
    {
        try {
            return DB::connection($this->db)
                ->table("sys_modules")
                ->select("sys_modules.module_name", DB::raw("GROUP_CONCAT(task_data.task_data_name, ',') as task"), DB::raw("GROUP_CONCAT(sys_tasks.task_id, ',') as taskid"))
                ->leftJoin('sys_tasks', 'sys_tasks.module_id', '=', 'sys_modules.module_id')
                ->leftJoin('task_data', 'task_data.task_data_id', '=', 'sys_tasks.task_data_id')
                ->groupBy('sys_modules.module_id')
                ->orderBy('module_name')
                ->get();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    // public function getModuleTask()
    // {
    //     try {
    //         return DB::connection($this->db)
    //             ->table("sys_modules")
    //             ->select("sys_modules.module_name", DB::raw("ARRAY_TO_STRING(ARRAY_AGG(sys_tasks.task_name), ',') as task"), DB::raw("ARRAY_TO_STRING(ARRAY_AGG(sys_tasks.task_id), ',') as taskid"))
    //             ->leftJoin('sys_tasks', 'sys_tasks.module_id', '=', 'sys_modules.module_id')
    //             ->groupBy('sys_modules.module_id')
    //             ->orderBy('sys_modules.module_name')
    //             ->get();
    //     } catch (Exception $e) {
    //         return $e->getMessage();
    //     }
    // }

    public function deleteByTask($group, $tasks)
    {
        try {
            return DB::connection($this->db)
                ->table($this->table)
                ->where('group_id', '=', $group)
                ->whereNotIn('task_id', $tasks)
                ->delete();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}
