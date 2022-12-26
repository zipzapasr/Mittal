<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Contractor;

class SiteContractor extends Model
{
    use HasFactory;
    protected $table = 'site_contractors';
    protected $fillable = ['site_id', 'contractor_id', 'status'];

    public function getContractor() {
        return $this->belongsTo(Contractor::class, 'contractor_id', 'id');
    }
}
