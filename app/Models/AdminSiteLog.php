<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Activity;
use App\Models\User;

class AdminSiteLog extends Model
{
    use HasFactory;
    protected $table = 'admin_site_log';

    protected $fillable = [
        'site_id',
        'activity_id',
        'updated_by_id',
        'value',
        'remarks',
        'status',
        'date'
    ];

    public $remarks = [
        'site_act_est_changed', // value is estimate qty
        'site_project_manager_changed', // value is project_manager_id
        'site_data_entry_operator_changed', // value is data_entry_operato_id
        'site_entry_changed',// value is site_entry details(JSON)
        'site_cement_purchase_changed',
        'site_cement_in_changed',
        'site_cement_out_changed',
        'site_cement_transfer_to_client_changed'
    ];

    public function getActivity(){
        return $this->belongsTo(Activity::class , 'activity_id' , 'id' );
    }

    public function getEmployee() {
        return $this->belongsTo(User::class , 'updated_by_id' , 'id' );
    }

    public function getSite() {
        return $this->belongsTo(Sites::class , 'site_id' , 'id' );
    }

    public function getProjectManager() {
        return $this->belongsTo(User::class , 'value' , 'id' );
    }

    public function getDataEntryOperator() {
        return $this->belongsTo(User::class , 'value' , 'id' );
    }
}
