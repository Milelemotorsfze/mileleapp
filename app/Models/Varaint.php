<?php
 
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Varaint extends Model
{
    use HasFactory;
    protected $table = 'varaints';
    protected $appends = [
        'is_deletable',
    ];
    public function vehicles()
    {
        return $this->hasMany(Vehicles::class);
    }
    public function availableColor()
    {
        return $this->hasOne(AvailableColour::class, 'varaint_id');
    }
    public function variantItems()
    {
        return $this->hasMany(VariantItems::class);
    }
    public function masterModel()
    {
        return $this->hasOne(MasterModel::class,'variant_id');
    }
    public function users()
    {
        return $this->belongsTo(User::class,'created_by');
    }
    public function master_model_lines()
    {
        return $this->belongsTo(MasterModelLines::class,'master_model_lines_id');
    }
    public function brand()
    {
        return $this->belongsTo(Brand::class,'brands_id');
    }
    public function getIsDeletableAttribute() {

        $variant = Varaint::find($this->id);
        $vehicles = Vehicles::where('varaints_id', $this->id)->get();
        if($vehicles->count() <= 0) {
            $loiItem = LetterOfIndentItem::with('masterModel')
                ->whereHas('masterModel', function ($query) use($variant) {
                $query->where('variant_id', $variant->id);
                })->get();
            if($loiItem->count() <= 0) {
                $demandLists = DemandList::with('masterModel')
                    ->whereHas('masterModel', function ($query) use($variant) {
                        $query->where('variant_id', $variant->id);
                    })->get();;
                if($demandLists->count() <= 0) {
                    $availableColors = AvailableColour::where('varaint_id', $this->id)->get();
                    if($availableColors->count() <= 0) {
                        return true;
                    }
                }
            }
        }
        return false;
    }
}
