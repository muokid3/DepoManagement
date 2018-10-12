<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    public function company()
    {
        return $this->belongsTo('App\Company', 'company_id');
    }

    public function currentDriver()
    {
        return VehicleDriver::where('vehicle_id',$this->id)->where('active', 1)->first();
    }

    public function previousDrivers()
    {
        return VehicleDriver::where('vehicle_id',$this->id)->where('active', 0)->get();
    }
}
