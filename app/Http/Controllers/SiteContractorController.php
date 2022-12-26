<?php

namespace App\Http\Controllers;
use App\Models\SiteContractor;

use Illuminate\Http\Request;

class SiteContractorController extends Controller
{
    public function save(Request $request) {
    }

    public function list() {
        dd(SiteContractor::all());
    }
}
