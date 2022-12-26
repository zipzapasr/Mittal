<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AdminSiteLog;1

class AdminSiteLogController extends Controller
{
    public function index()
    {
        dd(AdminSiteLog::all());
    }
}
