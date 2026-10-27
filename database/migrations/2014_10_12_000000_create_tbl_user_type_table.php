<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('tbl_user_type')) {
            Schema::create('tbl_user_type', function (Blueprint $table) {
                $table->bigIncrements('idtbl_user_type');
                $table->string('type')->nullable();
                $table->string('status')->default('active');
                $table->dateTime('updatedatetime')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_user_type');
    }
};
