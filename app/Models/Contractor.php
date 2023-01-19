<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contractor extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'business_name', 'mobile', 'details', 'status', 'identification_type', 'identification'
    ];
    protected $table = 'contractors';
    public $identification_types = [
        'Aadhar Card', 'Driving License', 'Voter Card'
    ];
}
