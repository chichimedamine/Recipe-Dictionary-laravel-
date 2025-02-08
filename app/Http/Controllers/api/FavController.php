<?php
namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Fav;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FavController extends Controller
{
    public function add($recipe_id)

    {
        $validator = Validator::make(['id' => $recipe_id], ['id' => 'exists:recipes,id']);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 400);
        }

        $fav = new Fav();
        $fav->user_id = Auth::user()->id;

        $exist = Fav::where('user_id', Auth::user()->id)
            ->where('recipe_id', $recipe_id)
            ->first();

        if($exist){
            return response()->json(["message" => "already exist"], 400);
        }else{
            $fav->recipe_id = (int) $recipe_id;

        }



        $fav->save();
        if(!$fav){
            return response()->json(["message" => "Failed to add favorite"], 400);
        }


        return response()->json($fav, 201);
    }

    public function remove($id)
    {
        $fav = Fav::where('user_id', Auth::user()->id)
        ->where('recipe_id', $id)->first();
        $exist= Fav::where('user_id', Auth::user()->id)
        ->where('recipe_id', $id)->first();
        if(!$exist){
            return response()->json(["message" => "not found"], 400);
        }else{
            $fav->delete();
        }

        return response()->json($fav);
    }

    public function index()
    {
        $favs = Fav::where('user_id', Auth::user()->id)->get();
        return response()->json($favs);
    }
    public function favStatus($id)
    {
        $fav = Fav::where('user_id', Auth::user()->id)
        ->where('recipe_id', $id)->first();
        if(!$fav){
            return response()->json(["Fav" => false], 400);
        }else{
            return response()->json(["Fav" => true]);
        }
    }
}
