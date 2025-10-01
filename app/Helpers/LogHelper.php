<?php 
/** 
 * Log Helpers
 * 
 *
 * @author Robby Al Jufri
 *
 */
namespace App\Helpers;

use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\DB;
use DB;

class LogHelper{

    public function __construct()
    {
        $this->db = 'mysql';
    }

	/**
	 * Normalize params
	 *
	 * @param
	 * - param module  <string>
	 * - param data <string>
	 * - param slug <string>
	 * 
	 * @return string
	 *
	 */
	public function store($module, $data, $slug){

        $now    = date('Y-m-d H:i:s');
        $id     = Auth::user()->user_id;
        $name   = Auth::user()->user_name;

        if($slug == 'create'){
            $key = "Menambahkan";
        }
        elseif($slug == 'update') {
            $key = "Merubah";
        }
        else{
            $key = "Menghapus";
        }        
        
        $description = $name .' '. $key .' '. $module .' - '. $data;

        try {
            return DB::connection($this->db)
                ->table('sys_log_activities')
                ->insert(['log_description' => $description,
                          'created_at' => $now,
                          'created_by' => $id
                        ]);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

}

?>