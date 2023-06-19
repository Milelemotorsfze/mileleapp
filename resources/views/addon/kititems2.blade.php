{{ $supplierAddonDetails->addon_type_name}}
{{ $supplierAddonDetails->K1}}
{{ $supplierAddonDetails->part_number}}
{{ $supplierAddonDetails->payment_condition}}
{{ $supplierAddonDetails->lead_time}}
{{ $supplierAddonDetails->additional_remarks}}
{{ $supplierAddonDetails->is_all_brands}}
{{ $supplierAddonDetails->image}}
{{ $supplierAddonDetails->fixing_charges_included}}
{{ $supplierAddonDetails->fixing_charge_amount}}

{{ $supplierAddonDetails->AddonName->name}}
{{ $supplierAddonDetails->AddonName->addon_type}}

{{ $supplierAddonDetails->SellingPrice->selling_price}}

{{ $supplierAddonDetails->LeastPurchasePrices->purchase_price_aed}}


@foreach($supplierAddonDetails->AddonSuppliers as $AddonSuppliers)
    {{ $AddonSuppliers->purchase_price_aed}}
    {{ $AddonSuppliers->Suppliers->supplier}}
    {{ $AddonSuppliers->Suppliers->contact_person}}
    {{ $AddonSuppliers->Suppliers->contact_number}}
    {{ $AddonSuppliers->Suppliers->alternative_contact_number}}
    {{ $AddonSuppliers->Suppliers->email}}
    {{ $AddonSuppliers->Suppliers->person_contact_by}}

    @foreach($AddonSuppliers->Kit as $Kit)
    {{ $Kit->addon->addon_type_name}}
    {{ $Kit->addon->addon_code}}
    {{ $Kit->addon->part_number}}
    {{ $Kit->addon->AddonName->name}}
    {{ $Kit->quantity}}
    {{ $Kit->unit_price_in_aed}}
    {{ $Kit->total_price_in_aed}}
    @endforeach

@endforeach


