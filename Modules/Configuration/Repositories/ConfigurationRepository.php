<?php

namespace Modules\Configuration\Repositories;

use App\Implementations\QueryBuilderImplementation;

class ConfigurationRepository extends QueryBuilderImplementation
{
    public $fillable = ['key', 'value', 'created_at', 'created_by', 'updated_at', 'updated_by'];

    public function __construct()
    {
        $this->table = 'configurations';
        $this->pk = 'configuration_id';
    }
}
