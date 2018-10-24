<?php

namespace App\Http\Controllers;

use App\Depot;
use App\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
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
        switch (Auth::user()->user_group) {
            case 1:
                //super admin
                return view('dashboard');
                break;
            case 2:
                //admin
                return view('dashboard');
                break;
            case 3:
                //company manager,
                return view('dashboard');

                break;
            case 4:
                //depot manager,
                if (is_null(Auth::user()->org_id)){
                    return view('errors/500');
                }else{
                    $depot = Depot::find(Auth::user()->org_id);
                    $orders = Order::where('depot_id', Auth::user()->org_id)->orderBy('id', 'desc')->paginate(20);
                    return view('orders/orders', ['orders' => $orders,'depot'=>$depot]);
                }
                break;
            case 5:
                //company clerks,
                echo json_encode(
                    DB::table('companies')->select('company_name as name', 'id')->get()
                );
                break;
            case 6:
                //depot clerk,
                echo json_encode(
                    DB::table('depots')->select('depot_name as name', 'id')->get()
                );
                break;
            default:
                //neither
                return view('errors/404');
        }


    }

    function get_orgs($usergroup_id)
    {

        switch ($usergroup_id) {
            case 3:
                //company manager, return companies
                echo json_encode(
                    DB::table('companies')->select('company_name as name', 'id')->get()
                );
                break;
            case 4:
                //depot manager, return depots
                echo json_encode(
                    DB::table('depots')->select('depot_name as name', 'id')->get()

                );
                break;
            case 5:
                //company clerks, return companies
                echo json_encode(
                    DB::table('companies')->select('company_name as name', 'id')->get()
                );
                break;
            case 6:
                //depot clerk, return depots
                echo json_encode(
                    DB::table('depots')->select('depot_name as name', 'id')->get()
                );
                break;
            default:
                //neither
                return Response::json(array(
                    'code'      =>  404,
                    'message'   =>  "Not found"
                ), 404);
        }
    }

}
