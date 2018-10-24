<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
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
        return view('dashboard');

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
