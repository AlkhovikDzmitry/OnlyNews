<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
{
    $categories = [
        'Юмор', 
        'Гаджеты', 
        'Секс', 
        'Девушки', 
        'Интересное', 
        'Новости', 
        'Авто', 
        'Дом', 
        'Креатив'
    ];

    foreach ($categories as $category) {
        \App\Models\Category::create([
            'name' => $category,
            'slug' => \Illuminate\Support\Str::slug($category)
        ]);
    }
}
}
