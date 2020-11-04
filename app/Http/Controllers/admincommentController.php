<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Comment;
use Illuminate\Support\Facades\Auth;

class admincommentController extends Controller
{
    public function store(Request $request)
    {
    	$request->validate([
            'comment'=>'required',
        ]);

        $comment = new Comment;

        $comment->comment     = $request->comment;
        $comment->category_id = $request->category_id;
        if(auth()->check())
        {
            return auth()->user()->id;
        }else{
            return 'amr';
        }
        $comment->member_id   = $request->member()->id;

        $comment->save();

        return back();
    }
}
