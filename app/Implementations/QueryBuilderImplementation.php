<?php

namespace App\Implementations;

use Illuminate\Support\Facades\DB;
use App\Contracts\CRUDContract;
use Exception;

class QueryBuilderImplementation implements CRUDContract
{

    protected $db;
    protected $table;
    protected $pk;
    protected $fillable;

    public function __construct()
    {
        $this->db = 'mysql';
        $this->table = null;
        $this->pk = null;
    }

    public function getAll()
    {
        try {
            return DB::connection($this->db)
                ->table($this->table)
                ->latest()
                ->get();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function getAllByParams(array $params)
    {
        try {
            return DB::connection($this->db)
                ->table($this->table)
                ->where($params)
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
                ->where($this->pk, '=', $id)
                ->first();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function getByParams(array $params)
    {
        try {
            return DB::connection($this->db)
                ->table($this->table)
                ->where($params)
                ->first();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function getFirst()
    {
        try {
            return DB::connection($this->db)
                ->table($this->table)
                ->first();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function getLast()
    {
        try {
            return DB::connection($this->db)
                ->table($this->table)
                ->orderBy($this->pk, 'desc')
                ->first();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function insert(array $data)
    {
        try {
            return DB::connection($this->db)
                ->table($this->table)
                ->insert($this->fillableMatch($data));
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function insertGetId(array $data, $returnId = 'id')
    {
        try {
            return DB::connection($this->db)
                ->table($this->table)
                ->insertGetId($data, $returnId);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function delete($id)
    {
        try {
            return DB::connection($this->db)
                ->table($this->table)
                ->where($this->pk, '=', $id)
                ->delete();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function update(array $data, $id)
    {
        try {
            return DB::connection($this->db)
                ->table($this->table)
                ->where($this->pk, '=', $id)
                ->update($this->fillableMatch($data));
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function truncate()
    {
        try {
            return DB::connection($this->db)
                ->table($this->table)
                ->truncate();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function runQuery($sql, array $params)
    {
        try {
            return DB::connection($this->db)->select($sql, $params);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function count()
    {
        try {
            return DB::connection($this->db)
                ->table($this->table)
                ->count();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function getSome($row, $page = 1)
    {
        try {
            return DB::connection($this->db)
                ->table($this->table)
                ->offset(($page == 1 ? 0 : ($page - 1)) * $row)
                ->limit($row)
                ->get();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function countByParams(array $params)
    {
        try {
            return DB::connection($this->db)
                ->table($this->table)
                ->where($params)
                ->count($this->pk);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    private function fillableMatch(array $params)
    {

        foreach ($params as $key => $value) {

            if (!in_array($key, $this->fillable)) {

                unset($params[$key]);
            }
        }
        return $params;
    }

    public function getAllGroupBy($params)
    {
        try {
            return DB::connection($this->db)
                ->table($this->table)
                ->groupBy($params)
                ->get();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}
