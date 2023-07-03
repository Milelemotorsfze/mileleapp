<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vendors = Vendor::cursor();
        return view('vendors.index', compact('vendors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('vendors.create');

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'vendor_type' => 'required',
            'trade_name_or_individual_name' => 'required',
            'passport_copy_file' => 'required_if:vendor_type,Individual',
            'nationality' => 'required_if:vendor_type,Individual',
            'passport_number' => 'required_if:vendor_type,Individual',
            'trade_registration_place' => 'required_if:vendor_type,Company',
            'trade_license_number' => 'required_if:vendor_type,Company',
            'trade_license_file' => 'required_if:vendor_type,Company',
            'Id_number' => 'required',
            'category' => 'required'
        ]);

        $vendor = new Vendor();
        $vendor->vendor_type = $request->vendor_type;
        $vendor->trade_name_or_individual_name = $request->trade_name_or_individual_name;
        $vendor->category = $request->category;
        $vendor->web_address = $request->web_address;
        $vendor->comment = $request->comment;
        $vendor->Id_number = $request->Id_number;
        $vendor->business_registration = $request->business_registration;
        $vendor->primary_subsidiary = $request->primary_subsidiary;
        $vendor->reference = $request->reference;
        $vendor->passport_number = $request->passport_number;
        $vendor->nationality = $request->nationality;
        $vendor->trade_license_number = $request->trade_license_number;
        $vendor->trade_registration_place = $request->trade_registration_place;
        $vendor->email = $request->email;
        $vendor->phone = $request->phone;
        $vendor->mobile = $request->mobile;
        $vendor->alternate_contact_number = $request->alternate_contact_number;
        $vendor->fax = $request->fax;
        $vendor->address_details = $request->address_details;
        $vendor->preference_id = $request->preference_id;
        $vendor->default_shipping_address = $request->default_shipping_address;
        $vendor->default_billing_address = $request->default_billing_address;
        $vendor->label = $request->label;
        $vendor->address = $request->address;
        $vendor->notes = $request->notes;
        $vendor->email_preference = $request->email_preference;
        $vendor->print_on_check_as = $request->print_on_check_as;
        $vendor->send_transaction_via = $request->send_transaction_via;
        $vendor->status = 'active';

        if ($request->hasFile('passport_copy_file'))
        {
            $file = $request->file('passport_copy_file');
            $extension = $file->getClientOriginalExtension();
            $fileName = time().'.'.$extension;
            $destinationPath = 'vendor/passport';
            $file->move($destinationPath, $fileName);
            $vendor->passport_copy_file = $fileName;
        }
        if ($request->hasFile('trade_license_file'))
        {
            $file = $request->file('trade_license_file');
            $extension = $file->getClientOriginalExtension();
            $fileName = time().'.'.$extension;
            $destinationPath = 'vendor/trade_license';
            $file->move($destinationPath, $fileName);
            $vendor->trade_license_file = $fileName;
        }
        if ($request->hasFile('vat_certificate_file'))
        {
            $file = $request->file('vat_certificate_file');
            $extension = $file->getClientOriginalExtension();
            $fileName = time().'.'.$extension;
            $destinationPath = 'vendor/vat_certificate';
            $file->move($destinationPath, $fileName);
            $vendor->vat_certificate_file = $fileName;
        }
        $vendor->save();

        return redirect()->route('vendors.index')->with('success','Vendor created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $vendor = Vendor::findOrFail($id);
        return view('vendors.edit', compact('vendor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'vendor_type' => 'required',
            'trade_name_or_individual_name' => 'required',
//            'nationality' => 'required_if:vendor_type,Individual'
        ]);

        $vendor = Vendor::findOrFail($id);
        $vendor->vendor_type = $request->vendor_type;
        $vendor->trade_name_or_individual_name = $request->trade_name_or_individual_name;
        $vendor->category = $request->category;
        $vendor->web_address = $request->web_address;
        $vendor->comment = $request->comment;
        $vendor->Id_number = $request->Id_number;
        $vendor->business_registration = $request->business_registration;
        $vendor->primary_subsidiary = $request->primary_subsidiary;
        $vendor->reference = $request->reference;
        $vendor->passport_number = $request->passport_number;
        $vendor->nationality = $request->nationality;
        $vendor->trade_license_number = $request->trade_license_number;
        $vendor->trade_registration_place = $request->trade_registration_place;
        $vendor->email = $request->email;
        $vendor->phone = $request->phone;
        $vendor->mobile = $request->mobile;
        $vendor->alternate_contact_number = $request->alternate_contact_number;
        $vendor->fax = $request->fax;
        $vendor->address_details = $request->address_details;
        $vendor->preference_id = $request->preference_id;
        $vendor->default_shipping_address = $request->default_shipping_address;
        $vendor->default_billing_address = $request->default_billing_address;
        $vendor->label = $request->label;
        $vendor->address = $request->address;
        $vendor->notes = $request->notes;
        $vendor->email_preference = $request->email_preference;
        $vendor->print_on_check_as = $request->print_on_check_as;
        $vendor->send_transaction_via = $request->send_transaction_via;
        $vendor->status = 'active';

        if ($request->hasFile('passport_copy_file'))
        {
            $file = $request->file('passport_copy_file');
            $extension = $file->getClientOriginalExtension();
            $fileName = time().'.'.$extension;
            $destinationPath = 'vendor/passport';
            $file->move($destinationPath, $fileName);
            $vendor->passport_copy_file = $fileName;
        }
        if ($request->hasFile('trade_license_file'))
        {
            $file = $request->file('trade_license_file');
            $extension = $file->getClientOriginalExtension();
            $fileName = time().'.'.$extension;
            $destinationPath = 'vendor/trade_license';
            $file->move($destinationPath, $fileName);
            $vendor->trade_license_file = $fileName;
        }
        if ($request->hasFile('vat_certificate_file'))
        {
            $file = $request->file('vat_certificate_file');
            $extension = $file->getClientOriginalExtension();
            $fileName = time().'.'.$extension;
            $destinationPath = 'vendor/vat_certificate';
            $file->move($destinationPath, $fileName);
            $vendor->vat_certificate_file = $fileName;
        }
        $vendor->save();

        return redirect()->route('vendors.index')->with('success','Vendor created successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
