<?php

namespace Modules\SysTask\Repositories;

use App\Implementations\QueryBuilderImplementation;
use Illuminate\Support\Facades\DB;

class TaskDataRepository extends QueryBuilderImplementation
{

    public $fillable = ['task_data_name', 'created_at', 'created_by', 'updated_at', 'updated_by'];

    public function __construct()
    {
        $this->table = 'task_data';
        $this->pk = 'task_data_id';
    }

    // public function create(array $data): bool
    // {
    //     return DB::table($this->table)->insert($data);
    // }

    public function getAll()
    {
        try {
            return DB::connection($this->db)
                ->table($this->table)
                ->get();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}
