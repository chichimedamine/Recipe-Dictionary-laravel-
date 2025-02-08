<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Reaction;
use Illuminate\Http\Request;

class ReactionController extends Controller
{
   public function getReaction(Request $request){

    $request->validate([
        'recipe_id' => 'exists:recipes,id',
        'user_id' => 'exists:users,id',
    ]);

    $reaction = Reaction::where('recipe_id', $request->recipe_id)
    ->first();

    if(!$reaction){


        return response()->json(["type" => "notexists"], 200);
    }else{
        if($reaction->type == 'like'){
            $data = [

                'type' => 'like' ,

            ] ;


        }else{
            $data = [

                'type' => 'not_like' ,

            ] ;
        }

    }
    return response()->json($data ,200);
   }
   public function LikeOrNot(Request $request){



    $reaction = Reaction::updateOrCreate([
        'user_id' => $request->user()->id,
        'recipe_id' => $request->recipe_id,
    ], [
        'type' => $request->type,
    ]);

    $reaction->save();
    $data = [
        'type' => $reaction->type
    ];

    return response()->json($data, 200);

   }

   public function CreateComment(Request $request){

    $request->validate([
        'recipe_id' => 'exists:recipes,id',
        'comment' => 'string',
    ]);

    $comment = Comment::updateOrCreate([
        'user_id' => $request->user()->id,
        'recipe_id' => $request->recipe_id,
    ], [
        'comment' => $request->comment,
    ]);

    return response()->json($comment);
   }
public function DeleteComment(Request $request){

    $request->validate([
        'comment_id' => 'exists:comments,id',
    ]);

    $comment = Comment::findOrFail($request->comment_id);
    $comment->delete();
    return response()->json(['message' => 'Comment deleted successfully!'], 200);
}

public function CommentsByRecipe($id){

    $comments =Comment::where('recipe_id', $id)->get();
    return response()->json($comments);

}



    }




