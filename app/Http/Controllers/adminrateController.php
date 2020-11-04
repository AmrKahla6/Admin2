<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class adminrateController extends Controller
{
    public function store(Request $request)
    {
    	$request->validate([
            'rate'=>'required',
        ]);

        $input = $request->all();
        $input['member_id'] = auth()->user()->id;

        Comment::create($input);

        return back();
    }
}
