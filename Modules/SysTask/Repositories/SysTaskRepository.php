<?php

namespace Modules\SysTask\Repositories;

use App\Implementations\QueryBuilderImplementation;
use Illuminate\Support\Facades\DB;

class SysTaskRepository extends QueryBuilderImplementation
{

    public $fillable = ['module_id', 'task_data_id', 'created_at', 'created_by', 'updated_at', 'updated_by'];

    public function __construct()
    {
        $this->table = 'sys_tasks';
        $this->pk = 'task_id';
    }

    public function getAll()
    {
        try {
            return DB::connection($this->db)
                ->table($this->table)
                ->join('sys_modules', 'sys_tasks.module_id', '=', 'sys_modules.module_id')
                ->join('task_data', 'sys_tasks.task_data_id', '=', 'task_data.task_data_id')
                ->get();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function getById($id)
    {
        try {
            return DB::connection($this->db)
                ->table($this->table)
                ->join('sys_modules', 'sys_tasks.module_id', '=', 'sys_modules.module_id')
                ->join('task_data', 'sys_tasks.task_data_id', '=', 'task_data.task_data_id')
                ->where($this->pk, '=', $id)
                ->first();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}
