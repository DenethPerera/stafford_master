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
        if (Schema::hasTable('style_samples') && !Schema::hasColumn('style_samples', 'season')) {
            Schema::table('style_samples', function (Blueprint $table) {
                $table->string('season', 100)->nullable()->after('sample_type_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('style_samples') && Schema::hasColumn('style_samples', 'season')) {
            Schema::table('style_samples', function (Blueprint $table) {
                $table->dropColumn('season');
            });
        }
    }
};
