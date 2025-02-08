<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Comment;

class CommentController extends Controller
{
    public function commentsByRecipe($id){

        $comments =Comment::where('recipe_id', $id)->get();

        return response()->json($comments);

    }

    public function createComment(Request $request){

        $request->validate([
            'recipe_id' => 'exists:recipes,id',
            'comment' => 'string',
        ]);

        $comment = new Comment([
            'user_id' => $request->user()->id,
            'recipe_id' => $request->recipe_id,
            'content' => $request->comment,
            'username' => $request->user()->name,
        ]);
        $comment->save();


        return response()->json([
            'message' => 'Comment created successfully',
            'comment' => $comment,
        ], 201);
    }

    public function deleteComment(Request $request){

        $request->validate([
            'comment_id' => 'exists:comments,id',
        ]);

        $comment = Comment::findOrFail($request->comment_id);
        $comment->delete();
        return response()->json(['message' => 'Comment deleted successfully!'], 200);
    }

}
