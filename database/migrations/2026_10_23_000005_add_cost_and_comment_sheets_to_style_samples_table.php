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
        Schema::table('style_samples', function (Blueprint $table) {
            if (!Schema::hasColumn('style_samples', 'cost_sheet')) {
                $table->string('cost_sheet', 255)->nullable()->after('qc_report');
            }
            if (!Schema::hasColumn('style_samples', 'comment_sheet')) {
                $table->string('comment_sheet', 255)->nullable()->after('cost_sheet');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('style_samples', function (Blueprint $table) {
            if (Schema::hasColumn('style_samples', 'comment_sheet')) {
                $table->dropColumn('comment_sheet');
            }
            if (Schema::hasColumn('style_samples', 'cost_sheet')) {
                $table->dropColumn('cost_sheet');
            }
        });
    }
};
