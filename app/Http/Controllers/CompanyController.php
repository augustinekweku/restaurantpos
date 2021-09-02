<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function getCompanies(Request $request)
    {
            return Company::get();
     
    }

    public function createCompany(Request $request)
    {
        //validate request
        $this->validate($request, [
            'company_name' => 'required',
            'about' => 'required',
        ]);
        return Company::create([
            'company_name' => $request->company_name,
            'about' => $request->about,
        ]);
    }

    public function editCompany(Request $request)
    {
        //validate request
        $this->validate($request, [
            'company_name' => 'required',
            'about' => 'required',
        ]);
        return Company::where('id', $request->id)->update([
            'company_name' => $request->company_name,
            'about' => $request->about,
        ]);
    }

    public function deleteCompany(Request $request)
    {
        //validate request
        $this->validate($request, [
            'id' => 'required'
        ]);
        return Company::where('id', $request->id)->delete();
    }




}
