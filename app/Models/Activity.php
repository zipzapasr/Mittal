<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;
    protected $fillable = [ 'activity_name' , 'activity_description' ,'unit' , 'status', 'activity_type' ];
    protected $table = 'activity';
    
    
    public function getUnits(){
        return $this->belongsTo(\App\Models\Unit::class , 'unit' , 'id' );
    }
}