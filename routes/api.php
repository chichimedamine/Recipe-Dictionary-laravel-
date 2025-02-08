<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RecipeController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ReactionController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\FavController;



Route::post('api/login', [AuthController::class, 'login']);
Route::post('api/register', [AuthController::class, 'register']);
Route::post('api/logout', [AuthController::class, 'logout']);
Route::get('api/recipes/{id}/image', [RecipeController::class, 'getImage']);


Route::middleware('auth:api')->group(function () {
    Route::get('api/recipes', [RecipeController::class, 'index']);
    Route::get('api/recipes/{id}', [RecipeController::class, 'show']);
    Route::post('api/recipes', [RecipeController::class, 'store']);
    Route::put('api/recipes/{id}', [RecipeController::class, 'update']);
    Route::delete('api/recipes/{id}', [RecipeController::class, 'destroy']);
    Route::get('api/recipes/category/{id}', [RecipeController::class, 'recipeByCategory']);




    //category routes
    Route::get('api/categories', [CategoryController::class, 'index']);
  Route::post('api/reaction/{id}', [ReactionController::class, 'LikeOrNot']);
  Route::get('api/reaction/{recipe_id}', [ReactionController::class, 'getReaction']);
  //comment routes

  Route::get('api/comment/{recipe_id}', [CommentController::class, 'commentsByRecipe']);
  Route::delete('api/comment/{id}', [CommentController::class, 'deleteComment']);
  Route::post('api/comment/', [CommentController::class, 'CreateComment']);

    Route::get('api/search', [RecipeController::class, 'recipesBySearch']);
    Route::get('api/favs', [FavController::class, 'index']);
    Route::post('api/favs/{recipe_id}', [FavController::class, 'add']);
    Route::delete('api/favs/{id}', [FavController::class, 'remove']);
    Route::get('api/favs/status/{id}', [FavController::class, 'favStatus']);


});

