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
    Schema::table('posts', function (Blueprint $table) {
        // 1. Сначала удаляем внешний ключ, если он существует
        $table->dropForeign(['author_id']);
        
        // 2. Затем удаляем сам столбец
        $table->dropColumn('author_id');
    });
}

public function down()
{
    Schema::table('posts', function (Blueprint $table) {
        // При откате миграции восстанавливаем столбец
        $table->unsignedBigInteger('author_id')->after('user_id');
        
        // И восстанавливаем внешний ключ (если нужно)
        // $table->foreign('author_id')->references('id')->on('users');
    });
}
};
