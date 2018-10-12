<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BlackList extends Model
{
    public function vehicle()
    {
        return $this->belongsTo('App\Vehicle', 'vehicle_id');
    }


    public function blacklister()
    {
        return $this->belongsTo('App\User', 'blacklister_id');
    }
}
