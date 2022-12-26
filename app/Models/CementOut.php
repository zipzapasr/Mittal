<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CementOut extends Model
{
    use HasFactory;
    protected $table = 'cement_out';
    protected $fillable = ['date', 'bags', 'from_site_id', 'to_site_id', 'remark', 'status'];

    public function getFromSite()
    {
        return $this->belongsTo(Sites::class, 'from_site_id', 'id');
    }

    public function getToSite()
    {
        return $this->belongsTo(Sites::class, 'to_site_id', 'id');
    }
}
