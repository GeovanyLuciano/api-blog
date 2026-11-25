<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\Post;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Categoria::factory()
        //     ->count(50)
        //     ->create();

        Post::factory()->count(10)->create();
    }
}
