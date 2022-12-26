<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EditAccess extends Model
{
    use HasFactory;
    protected $fillable = [
        'key', 'value', 'date', 'status'
    ];

    public $keys = [
        'edit_site_entry_on_date',
        'edit_cement_purchase_on_date',
        'edit_cement_in_on_date',
        'edit_cement_out_on_date',
        'edit_cement_transfer_to_client_on_date'
    ];
}
