<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CementSupplier extends Model
{
    use HasFactory;
    protected $table = 'cement_suppliers';
    protected $fillable = ['name', 'mobile', 'details', 'status'];
}
