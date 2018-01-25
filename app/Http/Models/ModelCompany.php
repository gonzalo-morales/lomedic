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
        parent::__construct($attributes);
    }
}