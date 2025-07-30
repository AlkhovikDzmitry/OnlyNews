<?php

namespace App\Http\Controllers;

class AboutController extends Controller
{
    public function index()
    {
        $teamMembers = [
            ['name' => 'Иван Иванов', 'role' => 'Главный редактор', 'avatar' => 'https://i.pravatar.cc/150?img=1'],
            ['name' => 'Петр Петров', 'role' => 'Технический писатель', 'avatar' => 'https://i.pravatar.cc/150?img=2'],
            ['name' => 'Анна Сидорова', 'role' => 'Корректор', 'avatar' => 'https://i.pravatar.cc/150?img=3'],
        ];

        return view('about', compact('teamMembers'));
    }
}