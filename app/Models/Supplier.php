<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "suppliers";
    protected $fillable = [
        'supplier',
        'contact_person',
        'contact_number',
        'alternative_contact_number',
        'email',
        'web_address',
        'type',
        'comment',
        'notes',
        'billing_address',
        'shipping_address',
        'prefered_label',
        'prefered_id',
        'vat_certificate_file',
        'trade_license_file',
        'passport_copy_file',
        'fax',
        'address',
        'nationality',
        'passport_number',
        'prefered_label',
        'prefered_id',
        'trade_registration_place',
        'trade_license_number',
        'is_communication_mobile',
        'is_communication_email',
        'is_communication_postal',
        'is_communication_fax',
        'is_communication_any',
        'person_contact_by',
        'supplier_type',
        'created_by',
        'updated_by',
        'deleted_by',
        'status'
    ];
    protected $appends = [
        'is_deletable',
        'sub_categories',
        'passport_file',
        'vat_file',
        'trade_license_file'

    ];
    public static function categories()
    {
        return [
            'Vehicles' => 'Vehicles',
            'Parts and Accessories' => 'Parts and Accessories',
            'Other' => 'Other'
         ];
    }
    public const SUPPLIER_STATUS_ACTIVE = 'active';
    public const SUPPLIER_STATUS_INACTIVE = 'inactive';
    public const SUPPLIER_TYPE_DEMAND_PLANNING = 'demand_planning';
    public const SUPPLIER_TYPE_SPARE_PARTS = 'spare_parts';
    public const SUPPLIER_TYPE_ACCESSORIES = 'accessories';
    public const SUPPLIER_TYPE_WARRANTY = 'warranty';
    public const SUPPLIER_CATEGORY_VEHICLES = 'Vehicles';
    public const SUPPLIER_CATEGORY_PARTS_AND_ACCESSORIES = 'Parts and Accessories';
    public const SUPPLIER_CATEGORY_OTHER = 'Other';
    public const SUPPLIER_SUB_CATEGORY_BULK = 'Bulk';
    public const SUPPLIER_SUB_CATEGORY_SMALL_SEGMENT = 'Small Segment';

    public function supplierAddons()
    {
        return $this->hasMany(SupplierAddons::class,'supplier_id','id');
    }
    public function supplierTypes()
    {
        return $this->hasMany(SupplierType::class);
    }
    public function paymentMethods()
    {
        return $this->hasMany(SupplierAvailablePayments::class,'supplier_id','id');
    }
    public function supplierDocuments()
    {
        return $this->hasMany(VendorDocument::class,'supplier_id','id');
    }
    public function getsubCategoriesAttribute() {

        $vendorSubCategories = SupplierType::where('supplier_id', $this->id)->pluck('supplier_type')->toArray();

        return [
            'demand_planning' => 'Demand Planning',
            'spare_parts' => 'Spare Parts',
            'accessories' => 'Accessories',
            'warranty' => 'Warranty',
            'Bulk' => 'Bulk',
            'Small Segment' => 'Small Segment'
        ];
    }
    public function getIsDeletableAttribute()
    {
        $supplierInventories = SupplierInventory::where('supplier_id', $this->id)->count();
        if($supplierInventories <= 0) {
            $demands = Demand::where('supplier_id', $this->id)->count();
            if($demands <= 0) {
                $letterOfIndents = LetterOfIndent::where('supplier_id', $this->id)->count();
                if($letterOfIndents <= 0) {
                    return true;
                }
            }
        }
        return false;
    }
}
