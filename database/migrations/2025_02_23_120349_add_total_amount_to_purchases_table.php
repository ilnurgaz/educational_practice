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
        Schema::table('purchases', function (Blueprint $table) {
            $table->decimal('total_amount', 10, 2)->nullable()->after('status');
        });
    }
    
    public function down()
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->dropColumn('total_amount');
        });
    }
};
