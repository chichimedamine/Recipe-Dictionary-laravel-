<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RecipeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('recipes')->insert([
            [
                'title' => 'Spaghetti Carbonara',
                'description' => 'A classic Italian pasta dish.',
                'ingredients' => 'Spaghetti, eggs, pancetta, parmesan cheese, black pepper',
                'instructions' => 'Cook spaghetti. Fry pancetta. Mix eggs and cheese. Combine all.',
                'category_id' => 1, // Adjust this based on your categories
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Chicken Curry',
                'description' => 'A flavorful and spicy chicken dish.',
                'ingredients' => 'Chicken, curry powder, coconut milk, onions, garlic',
                'instructions' => 'Cook onions and garlic. Add chicken and curry powder. Stir in coconut milk.',
                'category_id' => 2, // Adjust this based on your categories
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Add more recipes as needed
        ]);
    }
}
