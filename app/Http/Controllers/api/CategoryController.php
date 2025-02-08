<?php
namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Category;

class CategoryController extends Controller
{
   // Get all categories
public function index()
{
    // Adding new categories
    if (Category::count() == 0) {
        $categories = [
            ['name' => 'Soups', 'description' => 'A variety of soups'],
            ['name' => 'Salads', 'description' => 'Fresh salads'],
            ['name' => 'Pasta', 'description' => 'Different kinds of pasta'],
            ['name' => 'Grilled Foods', 'description' => 'Grilled dishes'],
            ['name' => 'Desserts', 'description' => 'Sweet desserts']
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
    return response()->json(Category::all());
}



}


?>
