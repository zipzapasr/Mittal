<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CementPurchase extends Model
{
    use HasFactory;
    protected $table = 'cement_purchase';
    protected $fillable = ['date', 'bags', 'supplier_id', 'employee_id', 'site_id', 'status', 'remark'];

    public function getSite()
    {
        return $this->belongsTo(Sites::class, 'site_id', 'id',);

    }

    public function getSupplier() {
        return $this->belongsTo(CementSupplier::class, 'supplier_id', 'id');
    }

    public function getEmployee() {
        return $this->belongsTo(User::class, 'employee_id', 'id' );
    }
}
