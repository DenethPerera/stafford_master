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
        Schema::create('tbl_user_privilege', function (Blueprint $table) {
            $table->bigIncrements('idtbl_user_privilege');
            $table->unsignedBigInteger('tbl_user_idtbl_user')->nullable();
            $table->unsignedBigInteger('tbl_menu_list_idtbl_menu_list')->nullable();
            $table->string('access_status')->nullable();
            $table->string('add')->nullable();
            $table->string('edit')->nullable();
            $table->string('statuschange')->nullable();
            $table->string('remove')->nullable();
            $table->string('status')->default('active');
            $table->string('approvestatus')->nullable();
            $table->string('checkstatus')->nullable();
            $table->dateTime('updatedatetime')->nullable();

            $table->foreign('tbl_user_idtbl_user')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('tbl_menu_list_idtbl_menu_list')->references('idtbl_menu_list')->on('tbl_menu_list')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_user_privilege');
    }
};
