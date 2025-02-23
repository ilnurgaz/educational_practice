<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::table('purchases', function (Blueprint $table) {
            $table->string('status')->default('pending'); // Добавляем столбец со значением по умолчанию
        });
    }

    public function down() {
        Schema::table('purchases', function (Blueprint $table) {
            $table->dropColumn('status'); // Удаляем столбец при откате миграции
        });
    }
};
