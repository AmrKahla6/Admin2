<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Rating;

class adminratringController extends Controller
{
    public function store(Request $request)
    {
    	$request->validate([
            'rating'=>'required|numeric',
        ]);

        $input = $request->all();
        $input['user_id'] = auth()->user()->id;

        Rating::create($input);

        return back();
    }
}
