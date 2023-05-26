<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Generator as Faker;
use Illuminate\Support\Str;
use App\Models\Products;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Products>
 */
class ProductsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        // $code = $this->faker->unique()->text(10);
        // $name = Str::random(10);

        
         $code = $this->faker->unique()->text(30);
         $code = preg_replace('/[0-9]+/', '', $code);
         $code = str_replace(" ", "", $code);
         $code = strtolower($code);
         
         $name = Str::random(10);
         $name = preg_replace('/[0-9]+/', '', $name); 
         $name = preg_replace('/[\'\/~`\!@#\$%\^&\*\(\)_\-\+=\{\}\[\]\|;:"\<\>,\.\?\\\]/', '', $name);     
        
        return [
            'name' =>  $name,
            'code' => $code,
            'category' => Str::random(10),
            'price' => rand(0, 1000),  
            'release_date' => now(),
        ];

    }
}



