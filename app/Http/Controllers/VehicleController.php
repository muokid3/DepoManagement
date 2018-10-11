<?php

namespace App\Http\Controllers;

use App\Vehicle;
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
}
