<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteTransfer extends Model
{
    use HasFactory;
    protected $table = 'site_transfers';
    protected $fillable = ['date', 'site_from', 'site_to', 'num_bags', 'status'];

    public function siteFromDetails()
    {
        return $this->belongsTo(\App\Models\Sites::class, 'site_from', 'id');
    }

    public function siteToDetails()
    {
        return $this->belongsTo(\App\Models\Sites::class, 'site_to', 'id');
    }
}
