<?php

namespace App\Http\Controllers;

use App\Models\Reaction;
use App\Models\Comment;
use App\Models\Idea;
use App\Mail\SendMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Auth;

class CommentController extends Controller
{
    // Comment create
    public function store(Request $request)
    {
        $comment = Comment::create([
            'description' => $request->comment_body,
            'user_id' => Auth::id(),
            'idea_id' => $request->idea_id,
            'annonymous' => $request->annonymous ? $request->annonymous : 0,
        ]);

        $sent_user = Idea::find($request->idea_id)->user;

        Mail::to($sent_user->email)->send(new SendMail($comment));

        // $sent_user->notify(new SendMail($comment));

        return back();
    
    }

    public function reactionStore(Request $request)
    {
        Reaction::updateOrCreate(
            ['user_id' => Auth::id(), 'idea_id' => $request->id],
            [
                'user_id' => Auth::id(),
                'created_by' => Auth::id(),
                'idea_id' => $request->id,
                'up_down' => $request->up_down,
            ]
        );
        return response()->json('success');

    }


}
