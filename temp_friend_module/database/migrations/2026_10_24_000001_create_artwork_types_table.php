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
        if (!Schema::hasTable('artwork_types')) {
            Schema::create('artwork_types', function (Blueprint $table) {
                $table->id();
                $table->string('name', 100)->unique();
                $table->string('code', 50)->nullable();
                $table->text('description')->nullable();
                $table->string('status', 20)->default('Active');
                $table->timestamps();
            });

            // Seed common apparel artwork types
            $initialTypes = [
                ['name' => 'Screen Print', 'code' => 'SP', 'description' => 'Plastisol, water-based, or discharge screen printing', 'status' => 'Active', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Embroidery', 'code' => 'EMB', 'description' => 'Direct flat or 3D puff embroidery', 'status' => 'Active', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Heat Transfer', 'code' => 'HT', 'description' => 'Vinyl or silicone thermal heat transfer', 'status' => 'Active', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Badge / Patch', 'code' => 'PCH', 'description' => 'Woven, rubber, leather, or chenille patches', 'status' => 'Active', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Sublimation', 'code' => 'SUB', 'description' => 'All-over or panel dye sublimation printing', 'status' => 'Active', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'High Density Print', 'code' => 'HDP', 'description' => 'Raised dimensional silicone or ink printing', 'status' => 'Active', 'created_at' => now(), 'updated_at' => now()],
            ];

            DB::table('artwork_types')->insert($initialTypes);
        }

        if (Schema::hasTable('style_artworks') && !Schema::hasColumn('style_artworks', 'artwork_type_id')) {
            Schema::table('style_artworks', function (Blueprint $table) {
                $table->foreignId('artwork_type_id')->nullable()->after('style_id')->constrained('artwork_types')->nullOnDelete();
            });

            // Map existing style_artworks to artwork_types if matching by name
            $types = DB::table('artwork_types')->get()->keyBy('name');
            $artworks = DB::table('style_artworks')->whereNull('artwork_type_id')->get();
            foreach ($artworks as $art) {
                if (isset($types[$art->type])) {
                    DB::table('style_artworks')->where('id', $art->id)->update(['artwork_type_id' => $types[$art->type]->id]);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('style_artworks') && Schema::hasColumn('style_artworks', 'artwork_type_id')) {
            Schema::table('style_artworks', function (Blueprint $table) {
                $table->dropForeign(['artwork_type_id']);
                $table->dropColumn('artwork_type_id');
            });
        }

        Schema::dropIfExists('artwork_types');
    }
};
