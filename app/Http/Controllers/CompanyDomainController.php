<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CompanyDomain;

class CompanyDomainController extends Controller
{

    public function index()
    {
        $domains = CompanyDomain::with(['creator', 'updater'])->get();
        return view('companyDomains.index', compact('domains'));
    }


    public function create()
    {
        return view('companyDomains.createEdit', ['isEdit' => false, 'domain' => null]);
    }


    public function store(Request $request)
    {
        $request->validate([
            'domain_name' => 'required|string|max:255|unique:company_domains',
            'assigned_company' => 'required|string|max:255',
            'domain_registrar' => 'required|string|max:255',
            'email_server' => 'nullable|string|max:255',
        ]);

        $lastDomain = CompanyDomain::orderBy('id', 'desc')->first();
        $nextCodeNumber = $lastDomain ? intval(substr($lastDomain->company_domain_code, 2)) + 1 : 1;
        $companyDomainCode = 'CD' . str_pad($nextCodeNumber, 4, '0', STR_PAD_LEFT);

        CompanyDomain::create([
            'domain_name' => $request->domain_name,
            'assigned_company' => $request->assigned_company,
            'domain_registrar' => $request->domain_registrar,
            'email_server' => $request->email_server,
            'company_domain_code' => $companyDomainCode,
            'created_by' => Auth::id(),
            'updated_at' => now(),
            'created_at' => now(),
        ]);

        return redirect()->route('companyDomains.index')->with('success', 'Company Domain created successfully.');
    }



    public function edit($id)
    {
        $domain = CompanyDomain::findOrFail($id);
        return view('companyDomains.createEdit', ['isEdit' => true, 'domain' => $domain]);
    }

    public function update(Request $request, $id)
    {
        $domain = CompanyDomain::findOrFail($id);

        $request->validate([
            'domain_name' => 'required|string|max:255|unique:company_domains,domain_name,' . $domain->id,
            'assigned_company' => 'required|string|max:255',
            'domain_registrar' => 'required|string|max:255',
            'email_server' => 'nullable|string|max:255',
        ]);

        $domain->update([
            'domain_name' => $request->domain_name,
            'assigned_company' => $request->assigned_company,
            'domain_registrar' => $request->domain_registrar,
            'email_server' => $request->email_server,
            'updated_by' => Auth::id(),
            'updated_at' => now(),
        ]);

        return redirect()->route('companyDomains.index')->with('success', 'Company Domain updated successfully.');
    }



    public function destroy($id)
    {
        $domain = CompanyDomain::findOrFail($id);
        $domain->delete();

        return redirect()->route('companyDomains.index')->with('success', 'Company Domain deleted successfully.');
    }
}
