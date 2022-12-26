<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteEntry extends Model
{
    use HasFactory;
    protected $table = 'site_entries';
    protected $fillable = ['activity_id', 'qty', 'skilled_workers', 'skilled_workers_overtime', 'unskilled_workers', 'unskilled_workers_overtime', 'images', 'remark', 'status', 'site_id', 'cement_bags', 'field_type_id', 'progress_date', 'contractor_id', 'wastage'];

    public function getSite() {
        return $this->belongsTo(\App\Models\Sites::class, 'site_id', 'id');
    }

    public function getFieldType() {
        return $this->belongsTo(\App\Models\FieldType::class, 'field_type_id', 'id');
    }

    public function getActivity() {
        return $this->belongsTo(\App\Models\Activity::class, 'activity_id', 'id');
    }

    public function getContractor() {
        return $this->belongsTo(\App\Models\Contractor::class, 'contractor_id', 'id');
    }

}
