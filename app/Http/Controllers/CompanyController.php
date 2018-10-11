<?php

namespace App\Http\Controllers;

use App\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class CompanyController extends Controller
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
        $companies= Company::orderBy('id', 'desc')->paginate(10);
        return view('companies.companies', ['companies' => $companies]);

    }

    function new_company(Request $request)
    {
        $this->validate($request, [
            'company_name' => 'required|max:50',
        ]);

        $name = $request->company_name;

        DB::transaction(function() use ($name) {
            $company = new Company();
            $company->company_name = $name;
            $company->saveOrFail();
            Session::flash("success", "Company created Successfully!");
        });

        return redirect('/companies');
    }

    function company($company_id)
    {
        $company = Company::find($company_id);
        if (is_null($company)){
            abort(404);
        }else{
            return view('companies.company')->with('company', $company);
        }
    }

    function update_company(Request $request)
    {
        $company = Company::find($request->company_id);
        if (is_null($company)){
            abort(404);
        }else{
            DB::transaction(function() use ($company, $request) {
                $company->company_name = $request->company_name;
                $company->update();
                Session::flash("success", "Company updated Successfully!");
            });
            return redirect()->back();

        }
    }

}
