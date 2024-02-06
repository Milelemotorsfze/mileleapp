<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModifiedVariants extends Model
{
    use HasFactory;
    protected $table = 'modified_variants';
    public function modifiedVariantItems()
{
    return $this->belongsTo(ModelSpecification::class, 'modified_variant_items');
}

public function addon()
{
    return $this->belongsTo(Addon::class, 'addons_id');
}
}
