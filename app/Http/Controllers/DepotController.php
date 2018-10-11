<?php

namespace App\Http\Controllers;

use App\Depot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class DepotController extends Controller
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
        $depots= Depot::orderBy('id', 'desc')->paginate(10);
        return view('depots.depots', ['depots' => $depots]);

    }


    function new_depot(Request $request)
    {
        $this->validate($request, [
            'depot_name' => 'required|max:50',
        ]);

        $name = $request->depot_name;

        DB::transaction(function() use ($name) {
            $depot = new Depot();
            $depot->depot_name = $name;
            $depot->saveOrFail();
            Session::flash("success", "Depot created Successfully!");
        });

        return redirect('/depots');
    }

    function depot($depot_id)
    {
        $depot = Depot::find($depot_id);
        if (is_null($depot)){
            abort(404);
        }else{
            return view('depots.depot')->with('depot', $depot);
        }
    }

    function update_depot(Request $request)
    {
        $depot = Depot::find($request->depot_id);
        if (is_null($depot)){
            abort(404);
        }else{
            DB::transaction(function() use ($depot, $request) {
                $depot->depot_name = $request->depot_name;
                $depot->update();
                Session::flash("success", "Depot updated Successfully!");
            });
            return redirect()->back();

        }
    }
}
