<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CementTransferToClient extends Model
{
    use HasFactory;
    protected $table = 'cement_transfer_to_client';
    protected $fillable = [
        'site_id', 'bags', 'date', 'remark', 'status', 'employee_id'
    ];

    public function getSite()
    {
        return $this->belongsTo(Sites::class, 'site_id', 'id',);

    }

    public function getEmployee() {
        return $this->belongsTo(User::class, 'employee_id', 'id' );
    }
}
