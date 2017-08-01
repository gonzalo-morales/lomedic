<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class ModelCompany extends Model
{
    /**
     * Create a new Eloquent model instance.
     *
     * @param  array  $attributes
     * @return void
     */
    function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setConnection( request()->company );
    }
}
