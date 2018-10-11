<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    public function vehicles()
    {
        return $this->hasMany('App\Vehicle', 'company_id');
    }
}
