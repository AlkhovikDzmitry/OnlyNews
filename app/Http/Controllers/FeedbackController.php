<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\FeedbackMail;

class FeedbackController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string',
        ]);

        // Здесь можно добавить отправку email или сохранение в БД
        // Например:
        Mail::to('alkhovik86@gmail.com')->send(new FeedbackMail($validated));

        return back()->with('success', 'Спасибо за ваше сообщение! Мы свяжемся с вами в ближайшее время.');
    }
}