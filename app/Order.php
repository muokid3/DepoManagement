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


    public function driver()
    {
        return $this->belongsTo('App\Driver', 'driver_id');
    }

    public function company()
    {
        return $this->belongsTo('App\Company', 'company_id');
    }
}
