<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::create('posts', function (Blueprint $table) {
        $table->engine = 'InnoDB'; // Явное указание движка
        
        $table->id();
        $table->string('title');
        $table->text('content');
        
        // Сначала создаем колонку без ограничения
        $table->unsignedBigInteger('category_id');
        $table->unsignedBigInteger('author_id');
        
        $table->timestamps();
    });

    // Добавляем внешние ключи отдельно
    Schema::table('posts', function (Blueprint $table) {
        $table->foreign('category_id')
              ->references('id')
              ->on('categories')
              ->onDelete('cascade');
              
        $table->foreign('author_id')
              ->references('id')
              ->on('users')
              ->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
