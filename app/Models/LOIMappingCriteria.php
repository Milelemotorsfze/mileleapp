<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LOIMappingCriteria extends Model
{
    use HasFactory;
    public $table = 'loi_mapping_criterias';

    public const TYPE_MONTH = "Month";
    public const TYPE_YEAR = "Year";
}
