<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contractor extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'business_name', 'mobile', 'details', 'status'
    ];
    protected $table = 'contractors';
}
