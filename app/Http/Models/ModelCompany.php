<?php
namespace App\Http\Models;

use App\Http\Models\ModelBase;

class ModelCompany extends ModelBase
{
    /**
     * Create a new Eloquent model instance.
     * @param  array  $attributes
     * @return void
     */
    function __construct(array $attributes = [])
    {
        $this->setConnection( request()->company );
        $this->schema = getSchema($this->connection);
        
        parent::__construct($attributes);
    }
}