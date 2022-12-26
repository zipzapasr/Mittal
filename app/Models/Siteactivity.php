<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siteactivity extends Model
{
    use HasFactory;
    protected $table = 'site_activity';
    protected $fillable = ['site_id','activity_id','qty','status'];
    
    public function getActivity(){
        return $this->belongsTo(\App\Models\Activity::class , 'activity_id' , 'id' );
    }
}