<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Recipe;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\DB;

class RecipeController extends Controller
{
    // Get all recipes
    public function index()
    {
        $recipes = Recipe::orderByDesc('created_at')->get();

        $recipes->transform(function ($recipe) {
           // dd($recipe->image);
           $ip = request()->ip();
            $recipe->image = "http://{$ip}:8000/api/recipes/{$recipe->id}/image/" ;


            return $recipe;
        });

        return response()->json($recipes);
    }


    public function recipeByCategory($id)
    {
        $recipes =Recipe::where('category_id', $id)->get();

        $recipes->transform(function ($recipe) {
           // dd($recipe->image);
           $ip = request()->ip();
            $recipe->image = "http://{$ip}:8000/api/recipes/{$recipe->id}/image/" ;


            return $recipe;
        });
        return response()->json($recipes);

    }





    // Get a specific recipe by ID
    public function show($id)
    {
        $recipe = Recipe::findOrFail($id);
        $ip = request()->ip();
        $recipe->image = "http://{$ip}:8000/api/recipes/{$recipe->id}/image/" ;





        return response()->json($recipe);
    }


    // Store a new recipe
    public function store(Request $request)
    {
        \Log::info('Incoming request data: ', $request->all());


        $recipe = new Recipe();
        $recipe->title = $request->input('title');
        $recipe->description = $request->input('description');
        $recipe->ingredients = $request->input('ingredients');
        $recipe->instructions = $request->input('instructions');
        $recipe->username = $request->input('username');

        $recipe->category_id = $request->input('category_id');

        if ($request->hasFile('image')) {





            $path = $request->image->store('storage/uploads','public');
            $recipe->image = basename($path);
            \Log::info('Stored image at: ' . $path);
            $recipe->image_path = 'uploads/' . $recipe->image;

        }

        try {
            $recipe->save();
        } catch (\Exception $e) {

            \Log::error('Error while storing recipe: ' . $e->getMessage());
            return response()->json(['error' => 'Error while storing recipe '.$e->getMessage()], 500);
        }

        return response()->json($recipe, 201);

    }

    // Update a recipe
    public function update(Request $request, $id)
    {
        $recipe = Recipe::findOrFail($id);

        $request->validate([
            'title' => 'string|max:255',
            'description' => 'string',
            'ingredients' => 'string',
            'instructions' => 'string',
            'category_id' => 'exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $recipe->fill($request->except('image'));

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($recipe->image) {
                Storage::disk('public')->delete('uploads/' . $recipe->image);
            }

            $image = $request->file('image');
            $filename = time() . '_' . $image->getClientOriginalName();
            $image->storeAs('uploads', $filename, 'public');
            $recipe->image = $filename;
        }

        $recipe->save();

        // Return the recipe with the full image URL
        $recipe->image = $recipe->image ? Storage::url('uploads/' . $recipe->image) : null;

        return response()->json([
            'message' => 'Recipe updated successfully!',
            'recipe' => $recipe
        ]);
    }

    // Delete a recipe
    public function destroy($id)
    {
        $recipe = Recipe::findOrFail($id);

        // Delete the image file if it exists
        if ($recipe->image) {
            Storage::disk('public')->delete('uploads/' . $recipe->image);
        }

        $recipe->delete();
        return response()->json(['message' => 'Recipe deleted successfully!'], 200);
    }

    // Get recipe image
    public function getImage($id)
    {
        // Get the recipe image by ID
        $recipe = Recipe::findOrFail($id);

        // Check if the image exists
        if (!$recipe->image) {
            return response()->json(['error' => 'Image not found'], 404);
        }

        $imagePath = 'storage/uploads/' . $recipe->image;
        if (!Storage::disk('public')->exists($imagePath)) {
            return response()->json(['error' => 'Image not found'], 404);
        }

        // Return the image as a response
        $image = Storage::disk('public')->get($imagePath);
        return response($image, 200)
            ->header('Content-Type', Storage::disk('public')->mimeType($imagePath));
    }
    public function recipesBySearch(Request $request){




        if($request->has('search')){

            $recipes = Recipe::where('title', 'like', "%{$request->search}%")
            ->get();

            $recipes->transform(function ($recipe) {
                // dd($recipe->image);
                $ip = request()->ip();
                $recipe->image = "http://{$ip}:8000/api/recipes/{$recipe->id}/image/" ;
                return $recipe;
            });

            return response()->json($recipes);
        }

    }
}
