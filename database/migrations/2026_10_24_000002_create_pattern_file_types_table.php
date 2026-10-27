<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('pattern_file_types')) {
            Schema::create('pattern_file_types', function (Blueprint $table) {
                $table->id();
                $table->string('name', 100)->unique();
                $table->string('code', 50)->nullable();
                $table->text('description')->nullable();
                $table->string('status', 20)->default('Active');
                $table->timestamps();
            });

            // Seed initial industry-standard pattern file types
            $initialTypes = [
                ['name' => 'DXF / AAMA Standard CAD', 'code' => 'DXF', 'description' => 'Universal apparel CAD exchange pattern format (.dxf / .rul)', 'status' => 'Active', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Gerber Accumark CAD', 'code' => 'GERBER', 'description' => 'Gerber Technology Accumark pattern model & piece archive (.zip / .p-blk)', 'status' => 'Active', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Lectra Modaris CAD', 'code' => 'LECTRA', 'description' => 'Lectra Modaris 2D/3D pattern design system (.mdl / .vet)', 'status' => 'Active', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Optitex PDS Pattern', 'code' => 'OPTITEX', 'description' => 'Optitex pattern design system model (.pds)', 'status' => 'Active', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Clo3D / Browzwear 3D CAD', 'code' => '3D-CAD', 'description' => '3D digital twin pattern simulation file (.zprj / .bw)', 'status' => 'Active', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'PDF Plotter / Spec Pattern', 'code' => 'PDF-PAT', 'description' => '1:1 scale printable plotter pattern marker or specification sheet', 'status' => 'Active', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Illustrator CAD Vector Spec', 'code' => 'AI-CAD', 'description' => 'Adobe Illustrator technical vector CAD artwork & pattern master (.ai / .eps)', 'status' => 'Active', 'created_at' => now(), 'updated_at' => now()],
            ];

            DB::table('pattern_file_types')->insert($initialTypes);
        }

        if (Schema::hasTable('style_samples')) {
            Schema::table('style_samples', function (Blueprint $table) {
                if (!Schema::hasColumn('style_samples', 'pattern_file_type_id')) {
                    $table->foreignId('pattern_file_type_id')->nullable()->after('sample_type_id')->constrained('pattern_file_types')->nullOnDelete();
                }
                if (!Schema::hasColumn('style_samples', 'pattern_file')) {
                    $table->string('pattern_file', 255)->nullable()->after('qc_report');
                }
                if (!Schema::hasColumn('style_samples', 'pattern_uploaded_at')) {
                    $table->timestamp('pattern_uploaded_at')->nullable()->after('pattern_file');
                }
                if (!Schema::hasColumn('style_samples', 'pattern_notes')) {
                    $table->text('pattern_notes')->nullable()->after('pattern_uploaded_at');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('style_samples')) {
            Schema::table('style_samples', function (Blueprint $table) {
                if (Schema::hasColumn('style_samples', 'pattern_file_type_id')) {
                    $table->dropForeign(['pattern_file_type_id']);
                    $table->dropColumn('pattern_file_type_id');
                }
                if (Schema::hasColumn('style_samples', 'pattern_file')) {
                    $table->dropColumn('pattern_file');
                }
                if (Schema::hasColumn('style_samples', 'pattern_uploaded_at')) {
                    $table->dropColumn('pattern_uploaded_at');
                }
                if (Schema::hasColumn('style_samples', 'pattern_notes')) {
                    $table->dropColumn('pattern_notes');
                }
            });
        }

        Schema::dropIfExists('pattern_file_types');
    }
};
