<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SysGroup extends Model
{
    protected $table 		= "sys_user_groups";
    protected $primaryKey 	= "group_id";
    protected $fillable 	= ['group_name'];
}
