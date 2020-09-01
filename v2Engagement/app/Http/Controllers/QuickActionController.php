<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class QuickActionController extends Controller
{

    public function index(Request $request)
    {

        return view('quick_action.index');
    }
}
