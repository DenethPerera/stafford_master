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

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $columnsToDrop = [];
            foreach (['username', 'status', 'imagepath', 'tbl_user_type_idtbl_user_type', 'updatedatetime', 'deleted_at'] as $col) {
                if (Schema::hasColumn('users', $col)) {
                    $columnsToDrop[] = $col;
                }
            }
            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};
