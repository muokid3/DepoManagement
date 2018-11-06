<?php

namespace App\Http\Controllers;

use App\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    function new_order(Request $request)
    {
        $this->validate($request, [
            'quantity' => 'required',
            'sms_code' => 'required',
        ]);


        DB::transaction(function() use ($request) {
            $order = new Order();
            $order->vehicle_id = $request->vehicle_id;
            $order->depot_id = $request->depot_id;
            $order->product_id = $request->product_id;
            $order->quantity = $request->quantity;
            $order->loaded = $request->has('loaded');
            $order->sms_code = $request->sms_code;
            $order->driver_id = $request->driver_id;
            $order->saveOrFail();
            Session::flash("success", "Order created Successfully!");

        });

        return redirect()->back();
    }

}
