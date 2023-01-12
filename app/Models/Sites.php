<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Model\Siteactivity;

class Sites extends Model
{
    use HasFactory;
    protected $fillable = [
        'serial_no',
        'site_name',
        'site_description',
        'site_location',
        'site_address',
        'site_admin',
        'employees',
        'status'
    ];
    protected $table = 'site';

    public function getSiteactivity() // activities entered by admin
    {
        return $this->hasMany(\App\Models\Siteactivity::class, 'site_id', 'id');
    }

    public function getSiteEntries() { // entries entered by data-entry-operator/project-manager
        return $this->hasMany(\App\Models\SiteEntry::class, 'site_id', 'id');
    }

    public function dataEntryOperator() {
        return $this->belongsTo(User::class, 'employees', 'id' );
    }

    public function projectManager()
    {
        return $this->belongsTo(User::class, 'site_admin', 'id');
    }

    public function getCementPurchases() {
        return $this->hasMany(CementPurchase::class, 'site_id', 'id');
    }

    public function getCementInsSelf() {
        return $this->hasMany(CementIn::class, 'to_site_id', 'id');
    }

    public function getCementInsOther() {
        return $this->hasMany(CementOut::class, 'to_site_id', 'id');
    }

    public function getCementOutsSelf() {
        return $this->hasMany(CementOut::class, 'from_site_id', 'id');
    }

    public function getCementOutsOther() {
        return $this->hasMany(CementIn::class, 'from_site_id', 'id');
    }

    public function getCementTransfersToClient() {
        return $this->hasMany(CementTransferToClient::class, 'site_id', 'id');
    }

    public function getEditLogs() {
        return $this->hasMany(AdminSiteLog::class, 'site_id', 'id');
    }

}
