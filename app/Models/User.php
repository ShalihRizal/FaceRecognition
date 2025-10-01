<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{

    protected $table         = "sys_users";
    protected $primaryKey     = "user_id";
    protected $fillable     = ['user_name', 'user_username', 'user_email', 'user_password', 'user_status', 'group_id', 'created_at', 'updated_at'];
}
