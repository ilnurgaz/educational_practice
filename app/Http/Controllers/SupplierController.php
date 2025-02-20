<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index() {
        return Supplier::all();
    }

    public function store(Request $request) {
        return Supplier::create($request->all());
    }
}

