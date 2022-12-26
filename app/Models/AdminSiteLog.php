<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminSiteLog extends Model
{
    use HasFactory;
    protected $table = 'admin_site_log';
    protected $fillable = [
        'site_id',
        'activity_id',
        'updated_by_id',
        'from',
        'to',
        'remarks',
        'status'
    ];
}
