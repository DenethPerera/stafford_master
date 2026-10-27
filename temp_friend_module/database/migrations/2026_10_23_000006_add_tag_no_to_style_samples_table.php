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
        if (Schema::hasTable('style_samples') && !Schema::hasColumn('style_samples', 'tag_no')) {
            Schema::table('style_samples', function (Blueprint $table) {
                $table->string('tag_no', 100)->nullable()->after('sample_code');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('style_samples') && Schema::hasColumn('style_samples', 'tag_no')) {
            Schema::table('style_samples', function (Blueprint $table) {
                $table->dropColumn('tag_no');
            });
        }
    }
};
