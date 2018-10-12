<?php

namespace App\Http\Controllers;

use App\Driver;
use App\Vehicle;
use App\VehicleDriver;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class VehicleController extends Controller
{
    /**
     * Create a new controller instance.
     *
     */
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index()
    {
        $vehicles= Vehicle::orderBy('id', 'desc')->paginate(10);
        return view('vehicles.vehicles', ['vehicles' => $vehicles]);

    }


    function new_vehicle(Request $request)
    {
        $this->validate($request, [
            'company_id' => 'required',
            'license_plate' => 'required|max:20',
            'capacity' => 'required|max:10',
            'calibration_chart' => 'file|max:5000',
            'image' => 'file|max:5000',
        ]);


        DB::transaction(function() use ($request) {
            $destinationPath = 'uploads';
            $current_time = Carbon::now()->timestamp;

            $calibration_chart = $request->file('calibration_chart');
            $image = $request->file('image');

            if ($calibration_chart->move($destinationPath,$current_time.'-'.$calibration_chart->getClientOriginalName()) &&
                $image->move($destinationPath,$current_time.'-'.$calibration_chart->getClientOriginalName())){

                $vehicle = new Vehicle();
                $vehicle->company_id = $request->company_id;
                $vehicle->license_plate = $request->license_plate;
                $vehicle->capacity = $request->capacity;
                $vehicle->calibration_chart = 'uploads/'.$current_time.'-'.$calibration_chart->getClientOriginalName();
                $vehicle->image_link = 'uploads/'.$current_time.'-'.$image->getClientOriginalName();
                $vehicle->saveOrFail();
                Session::flash("success", "Vehicle created Successfully!");
            }else{
                Session::flash("error", "An error occurred when upload the files, please try again");
            }

        });

        return redirect('/vehicles');
    }

    function vehicle($vehicle_id)
    {
        $vehicle = Vehicle::find($vehicle_id);
        if (is_null($vehicle)){
            abort(404);
        }else{
            return view('vehicles.vehicle')->with('vehicle', $vehicle);
        }
    }

    function update_vehicle(Request $request)
    {
        $this->validate($request, [
            'company_id' => 'required',
            'license_plate' => 'required|max:20',
            'capacity' => 'required|max:10',
        ]);


        $vehicle = Vehicle::find($request->vehicle_id);
        if (is_null($vehicle)){
            abort(404);
        }else{
            DB::transaction(function() use ($vehicle, $request) {
                $vehicle->company_id = $request->company_id;
                $vehicle->license_plate = $request->license_plate;
                $vehicle->capacity = $request->capacity;
                $vehicle->update();
                Session::flash("success", "Vehicle updated Successfully!");
            });
            return redirect()->back();

        }
    }

    function assign_driver(Request $request)
    {
        $this->validate($request, [
            'vehicle_id' => 'required',
            'driver_id' => 'required',
        ]);


        $vehicle = Vehicle::find($request->vehicle_id);
        $driver = Driver::find($request->driver_id);
        if (is_null($vehicle) || is_null($driver)){
            abort(404);
        }else{
            DB::transaction(function() use ($vehicle, $driver, $request) {
                $vehicleDriver = VehicleDriver::where('vehicle_id', $request->vehicle_id)->where('driver_id', $request->driver_id)->first();

                if (is_null($vehicleDriver)){

                    DB::table('vehicle_drivers')
                        ->where('vehicle_id', '=', $request->vehicle_id)
                        ->update(array('active' => 0));

                    $newVehicleDriver = new VehicleDriver();
                    $newVehicleDriver->vehicle_id = $request->vehicle_id;
                    $newVehicleDriver->driver_id = $request->driver_id;
                    $newVehicleDriver->saveOrFail();

                    Session::flash("success", "Driver assigned Successfully!");

                }else{


                    if ($vehicleDriver->active){
                        Session::flash("message", $driver->name." is already assigned to this vehicle");

                    }else{
                        DB::table('vehicle_drivers')
                            ->where('vehicle_id', '=', $request->vehicle_id)
                            ->update(array('active' => 0));
                        $vehicleDriver->active = 1;
                        $vehicleDriver->update();
                        Session::flash("success", "Driver re-assigned Successfully!");

                    }

                }

            });
            return redirect()->back();

        }
    }


    function revoke_driver($vehicle_id, $driver_id)
    {
        $vehicle = Vehicle::find($vehicle_id);
        if (is_null($vehicle)){
            abort(404);
        }else{
            DB::transaction(function() use ($vehicle_id, $driver_id) {
                $vehicleDriver = VehicleDriver::where('vehicle_id', $vehicle_id)->where('driver_id', $driver_id)->first();

                if (is_null($vehicleDriver)){
                    Session::flash("message", "Driver does not exist");
                }else{

                    $vehicleDriver->active = 0;
                    $vehicleDriver->update();
                    Session::flash("success", "Driver has been revoked");


//                    if ($vehicleDriver->active){
//                        Session::flash("message", $driver->name." is already assigned to this vehicle");
//
//                    }else{
//                        DB::table('vehicle_drivers')
//                            ->where('vehicle_id', '=', $request->vehicle_id)
//                            ->update(array('active' => 0));
//                        $vehicleDriver->active = 1;
//                        $vehicleDriver->update();
//                        Session::flash("success", "Driver re-assigned Successfully!");
//
//                    }

                }

            });
            return view('vehicles.vehicle')->with('vehicle', $vehicle);


        }
    }
}
