<?php

namespace App\Http\Controllers;

use App\Driver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class DriverController extends Controller
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
        $drivers= Driver::orderBy('id', 'desc')->paginate(10);
        return view('drivers.drivers', ['drivers' => $drivers]);

    }


    function new_driver(Request $request)
    {
        $this->validate($request, [
            'driver_name' => 'required|max:50',
            'id_no' => 'required|max:20',
            'phone_no' => 'required|max:12',
        ]);


        DB::transaction(function() use ($request) {
            $driver = new Driver();
            $driver->name = $request->driver_name;
            $driver->id_no = $request->id_no;
            $driver->phone_no = $request->phone_no;
            $driver->saveOrFail();
            Session::flash("success", "Driver created Successfully!");
        });

        return redirect('/drivers');
    }

    function driver($driver_id)
    {
        $driver = Driver::find($driver_id);
        if (is_null($driver)){
            abort(404);
        }else{
            return view('drivers.driver')->with('driver', $driver);
        }
    }

    function update_driver(Request $request)
    {
        $this->validate($request, [
            'driver_name' => 'required|max:50',
            'id_no' => 'required|max:20',
            'phone_no' => 'required|max:12',
        ]);

        $driver = Driver::find($request->driver_id);
        if (is_null($driver)){
            abort(404);
        }else{
            DB::transaction(function() use ($driver, $request) {
                $driver->name = $request->driver_name;
                $driver->id_no = $request->id_no;
                $driver->phone_no = $request->phone_no;
                $driver->update();
                Session::flash("success", "Driver updated Successfully!");
            });
            return redirect()->back();

        }
    }
}
