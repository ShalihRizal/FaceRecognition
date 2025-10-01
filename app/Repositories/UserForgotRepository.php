<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use App\Implementations\QueryBuilderImplementation;

class UserForgotRepository extends QueryBuilderImplementation
{

    public $fillable = ['user_id', 'forgot_status', 'created_at', 'created_by', 'updated_at', 'updated_by'];

    public function __construct()
    {
        $this->table = 'user_forgots';
        $this->pk = 'forgot_id';
    }

}