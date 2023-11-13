<?php

namespace App\Models\HRM\Employee;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketAllowance extends Model
{
    use HasFactory;
    protected $table = "ticket_allowances";
    protected $fillable = [
        'employee_id',
        'po_year',
        'po_number',
        'status',
        'created_by',
        'updated_by',
        'deleted_by'
    ];
}
