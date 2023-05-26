<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Products;
use App\Models\Tags;
use Database\Factories\ProductsFactory;
use Database\Factories\TagFactory;
use App\Models\Test;
class CatalogDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Products::factory()->count(10)->has(Tags::factory()->count(3))->create();        

    }
}
