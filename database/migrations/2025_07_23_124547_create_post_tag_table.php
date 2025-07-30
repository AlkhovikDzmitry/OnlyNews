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
    Schema::create('post_tag', function (Blueprint $table) {
        $table->engine = 'InnoDB';
        
        // Сначала создаем колонки без ограничений
        $table->unsignedBigInteger('post_id');
        $table->unsignedBigInteger('tag_id');
        $table->primary(['post_id', 'tag_id']);
    });

    // Затем добавляем внешние ключи отдельным запросом
    Schema::table('post_tag', function (Blueprint $table) {
        $table->foreign('post_id')
              ->references('id')
              ->on('posts')
              ->onDelete('cascade');
              
        $table->foreign('tag_id')
              ->references('id')
              ->on('tags')
              ->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tags');
    }
};
