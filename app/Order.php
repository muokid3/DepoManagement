<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public function vehicle()
    {
        return $this->belongsTo('App\Vehicle', 'vehicle_id');
    }
    public function depot()
    {
        return $this->belongsTo('App\Depot', 'depot_id');
    }

    public function product()
    {
        return $this->belongsTo('App\Product', 'depot_id');
    }

    public function driver()
    {
        return $this->belongsTo('App\Driver', 'driver_id');
    }
}
