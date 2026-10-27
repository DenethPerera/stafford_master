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
        // 1. style_images
        Schema::create('style_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('style_id')->constrained('styles')->cascadeOnDelete();
            $table->string('image_type', 50)->default('Front');
            $table->string('image_path', 255);
            $table->boolean('is_primary')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // 2. style_tech_packs
        Schema::create('style_tech_packs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('style_id')->constrained('styles')->cascadeOnDelete();
            $table->string('fitting', 100)->nullable();
            $table->text('stitching')->nullable();
            $table->text('label_branding')->nullable();
            $table->text('care_instructions')->nullable();
            $table->text('packaging_notes')->nullable();
            $table->text('drawcord_notes')->nullable();
            $table->text('seam_notes')->nullable();
            $table->text('thread_notes')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
        });

        // 3. style_bom_items
        Schema::create('style_bom_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('style_id')->constrained('styles')->cascadeOnDelete();
            $table->string('component_type', 100);
            $table->string('item_description', 255);
            $table->string('supplier', 150)->nullable();
            $table->string('placement', 150)->nullable();
            $table->string('color', 100)->nullable();
            $table->decimal('qty_per_size', 10, 4)->default(1);
            $table->string('uom', 50)->default('Pcs');
            $table->decimal('unit_cost', 10, 4)->default(0);
            $table->decimal('total_cost', 10, 4)->default(0);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // 4. style_measurements
        Schema::create('style_measurements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('style_id')->constrained('styles')->cascadeOnDelete();
            $table->string('measurement_point', 255);
            $table->decimal('spec_value', 8, 2)->default(0);
            $table->decimal('tol_minus', 8, 2)->default(0.5);
            $table->decimal('tol_plus', 8, 2)->default(0.5);
            $table->decimal('xs', 8, 2)->nullable();
            $table->decimal('s', 8, 2)->nullable();
            $table->decimal('m', 8, 2)->nullable();
            $table->decimal('l', 8, 2)->nullable();
            $table->decimal('xl', 8, 2)->nullable();
            $table->decimal('xxl', 8, 2)->nullable();
            $table->decimal('3xl', 8, 2)->nullable();
            $table->decimal('4xl', 8, 2)->nullable();
            $table->decimal('5xl', 8, 2)->nullable();
            $table->decimal('6xl', 8, 2)->nullable();
            $table->string('uom', 20)->default('Inch');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // 5. style_colorways
        Schema::create('style_colorways', function (Blueprint $table) {
            $table->id();
            $table->foreignId('style_id')->constrained('styles')->cascadeOnDelete();
            $table->string('color_name', 100);
            $table->string('pantone_code', 100)->nullable();
            $table->string('lab_op_ref', 100)->nullable();
            $table->string('swatch_color_hex', 20)->default('#1e293b');
            $table->string('swatch_image', 255)->nullable();
            $table->string('status', 50)->default('Approved');
            $table->timestamps();
        });

        // 6. style_samples
        Schema::create('style_samples', function (Blueprint $table) {
            $table->id();
            $table->foreignId('style_id')->constrained('styles')->cascadeOnDelete();
            $table->string('sample_name', 100);
            $table->string('sample_code', 100)->nullable();
            $table->date('sent_date')->nullable();
            $table->string('status', 50)->default('Pending');
            $table->string('submitted_by', 100)->nullable();
            $table->date('approval_date')->nullable();
            $table->text('remarks')->nullable();
            $table->string('sample_image', 255)->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // 7. style_artworks
        Schema::create('style_artworks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('style_id')->constrained('styles')->cascadeOnDelete();
            $table->string('artwork_name', 150);
            $table->string('artwork_code', 100)->nullable();
            $table->string('type', 100)->default('Screen Print');
            $table->string('placement', 150)->nullable();
            $table->dimensions = null;
            $table->string('dimensions', 100)->nullable();
            $table->json('colors')->nullable();
            $table->string('artwork_file', 255)->nullable();
            $table->timestamps();
        });

        // 8. style_costings
        Schema::create('style_costings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('style_id')->constrained('styles')->cascadeOnDelete();
            $table->string('currency', 10)->default('USD');
            $table->json('material_costs')->nullable();
            $table->json('manufacturing_costs')->nullable();
            $table->decimal('overhead_percent', 5, 2)->default(10.00);
            $table->decimal('overhead_amount', 10, 4)->default(0);
            $table->decimal('profit_percent', 5, 2)->default(15.00);
            $table->decimal('profit_amount', 10, 4)->default(0);
            $table->decimal('total_direct_cost', 10, 4)->default(0);
            $table->decimal('total_cost', 10, 4)->default(0);
            $table->decimal('target_price', 10, 4)->default(0);
            $table->timestamps();
        });

        // 9. style_files
        Schema::create('style_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('style_id')->constrained('styles')->cascadeOnDelete();
            $table->string('file_name', 255);
            $table->string('file_type', 50)->default('PDF');
            $table->string('file_size', 50)->default('1.0 MB');
            $table->string('folder', 100)->default('Tech Pack');
            $table->string('file_path', 255);
            $table->string('uploaded_by', 100)->nullable();
            $table->timestamps();
        });

        // 10. style_comments
        Schema::create('style_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('style_id')->constrained('styles')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('comment');
            $table->boolean('is_new')->default(false);
            $table->timestamps();
        });

        // 11. style_revisions
        Schema::create('style_revisions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('style_id')->constrained('styles')->cascadeOnDelete();
            $table->string('revision_no', 20);
            $table->string('status', 50)->default('DEVELOPMENT');
            $table->text('changes_summary')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // 12. style_approvals
        Schema::create('style_approvals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('style_id')->constrained('styles')->cascadeOnDelete();
            $table->string('gate_name', 150);
            $table->string('status', 50)->default('Pending');
            $table->string('approved_by', 100)->nullable();
            $table->timestamp('approval_date')->nullable();
            $table->text('remarks')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('style_approvals');
        Schema::dropIfExists('style_revisions');
        Schema::dropIfExists('style_comments');
        Schema::dropIfExists('style_files');
        Schema::dropIfExists('style_costings');
        Schema::dropIfExists('style_artworks');
        Schema::dropIfExists('style_samples');
        Schema::dropIfExists('style_colorways');
        Schema::dropIfExists('style_measurements');
        Schema::dropIfExists('style_bom_items');
        Schema::dropIfExists('style_tech_packs');
        Schema::dropIfExists('style_images');
    }
};

