<?php

namespace Modules\Face\Repositories;

use App\Implementations\QueryBuilderImplementation;
use Exception;
use Illuminate\Support\Facades\DB;

class FaceRepository extends QueryBuilderImplementation
{
    public $fillable = ['face_id', 'face_user_id', 'face_image'];

    public function __construct()
    {
        $this->table = 'face';
        $this->pk = 'face_id';
    }

    public function getAll()
    {
        try {
            return DB::connection($this->db)
                ->table($this->table)
                ->join('sys_users', 'sys_users.user_id', '=', 'face.face_user_id')
                // ->where('sys_users.group_id', '!=', '1')
                ->get();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}
