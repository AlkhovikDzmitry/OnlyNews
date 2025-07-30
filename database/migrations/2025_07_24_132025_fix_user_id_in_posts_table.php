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
        // 1. Добавляем столбец как nullable
        if (!Schema::hasColumn('posts', 'user_id')) {
            $table->unsignedBigInteger('user_id')->nullable()->after('id');
        }
    });

    // 2. Заполняем существующие записи
    DB::table('posts')->update(['user_id' => \App\Models\User::first()->id ?? 1]);

    // 3. Добавляем ограничение NOT NULL и внешний ключ
    Schema::table('posts', function (Blueprint $table) {
        $table->unsignedBigInteger('user_id')->nullable(false)->change();
        
        $table->foreign('user_id')
              ->references('id')
              ->on('users')
              ->onDelete('cascade');
    });
}

public function down()
{
    Schema::table('posts', function (Blueprint $table) {
        $table->dropForeign(['user_id']);
        $table->dropColumn('user_id');
    });
}
};
