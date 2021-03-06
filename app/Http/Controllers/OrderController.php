<?php

namespace App\Http\Controllers;

use App\Compartment;
use App\Depot;
use App\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use PHPUnit\Util\Json;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    function new_order(Request $request)
    {
        $this->validate($request, [
            //'quantity' => 'required',
        ]);


        DB::transaction(function() use ($request) {

            $destinationPath = 'orders';


            $order = new Order();
            $order->vehicle_id = $request->vehicle_id;
            $order->depot_id = $request->depot_id;
            $order->company_id = $request->company_id;
            $order->loaded = 0;
            $order->driver_id = $request->driver_id;

            if ($request->file('order_document')){
                $image_time = Carbon::now()->addSeconds(13)->timestamp;
                $image = $request->file('order_document');
                $imageLink ='orders/'.$image_time.'-'.$image->getClientOriginalName();
                $image->move($destinationPath,$image_time.'-'.$image->getClientOriginalName());

                $order->order_document = $imageLink;
            }

            $itemsData = [];
            foreach (Compartment::where('vehicle_id', $request->vehicle_id)->get() as $compartment)
            {
                $tikon = "product_".$compartment->id;
                $lala = "quantity_".$compartment->id;

                $itemsData[] = [
                    'compartment_id' => $compartment->id,
                    'product' => $request->$tikon,
                    'quantity' => $request->$lala
                ];
            }


            $order->items  = json_encode($itemsData);



            if ($order->saveOrFail()){
                //notify driver
                $message = "Dear ". $order->driver->name.", your order has been initiated at ". $order->depot->depot_name.". You will receive an SMS code once the order is ready for shipping";
                $order->driver->sendSMS($message);
            }

            Session::flash("success", "Order created Successfully!");

        });

        return redirect()->back();
    }

    function view_order($order_id)
    {
        $order = Order::find($order_id);
        $depot = Depot::find(Auth::user()->org_id);

        if (is_null($order)){
            abort(404);
        }else{
            return view('orders.order')->with('order', $order)
                ->with('depot', $depot);
        }
    }


    function mark_loaded($order_id)
    {
        $order = Order::find($order_id);

        if (is_null($order)){
            abort(404);
        }

        $smsCode = '#'.str_pad($order->id, 4, "0", STR_PAD_LEFT);
        $order->sms_code =  $smsCode;
        $order->loaded =  1;
        $order->update();

        //notify driver with code
        $message = "Dear ". $order->driver->name.", your order has been loaded at ". $order->depot->depot_name." and is ready for shipping. Use code ".$smsCode . " at the depot.";
        $order->driver->sendSMS($message);

        return redirect()->back();

    }

}
