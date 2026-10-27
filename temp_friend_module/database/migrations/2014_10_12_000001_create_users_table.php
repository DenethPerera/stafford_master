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
        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->unique();
                $table->string('username')->nullable()->unique();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
                $table->string('status')->default('Active');
                $table->string('imagepath')->nullable();
                $table->unsignedBigInteger('tbl_user_type_idtbl_user_type')->nullable();
                $table->dateTime('updatedatetime')->nullable();
                $table->rememberToken();
                $table->timestamps();
                $table->softDeletes();

                $table->foreign('tbl_user_type_idtbl_user_type')
                      ->references('idtbl_user_type')
                      ->on('tbl_user_type')
                      ->onDelete('set null');
            });
        } else {
            Schema::table('users', function (Blueprint $table) {
                if (!Schema::hasColumn('users', 'username')) {
                    $table->string('username')->nullable()->unique()->after('email');
                }
                if (!Schema::hasColumn('users', 'status')) {
                    $table->string('status')->default('Active')->after('password');
                }
                if (!Schema::hasColumn('users', 'imagepath')) {
                    $table->string('imagepath')->nullable()->after('status');
                }
                if (!Schema::hasColumn('users', 'tbl_user_type_idtbl_user_type')) {
                    $table->unsignedBigInteger('tbl_user_type_idtbl_user_type')->nullable()->after('imagepath');
                }
                if (!Schema::hasColumn('users', 'updatedatetime')) {
                    $table->dateTime('updatedatetime')->nullable()->after('tbl_user_type_idtbl_user_type');
                }
                if (!Schema::hasColumn('users', 'deleted_at')) {
                    $table->softDeletes();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
