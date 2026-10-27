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
        Schema::create('styles', function (Blueprint $table) {
            $table->id();
            $table->string('style_no', 100)->unique()->index();
            $table->string('design_no', 100)->nullable();
            $table->string('style_name', 255);
            $table->foreignId('client_id')->nullable()->constrained('clients')->nullOnDelete();
            $table->string('season', 100)->nullable();
            $table->string('program', 150)->nullable();
            $table->string('category', 150)->nullable();
            $table->string('gender', 50)->nullable();
            $table->string('size_range', 100)->nullable()->default('XS - 6XL');
            $table->string('hts_code', 50)->nullable();
            $table->string('unit', 50)->nullable()->default('Inch');
            $table->text('content')->nullable();
            $table->string('gsm', 50)->nullable();
            $table->string('construction', 100)->nullable();
            $table->string('gauge', 50)->nullable();
            $table->string('sleeve', 100)->nullable();
            $table->string('hood', 100)->nullable();
            $table->string('pocket', 100)->nullable();
            $table->text('description')->nullable();
            $table->json('tags')->nullable();
            $table->text('packing_instructions')->nullable();
            $table->text('special_instructions')->nullable();
            $table->string('ship_to', 255)->nullable();
            $table->string('country', 100)->nullable();
            $table->string('port', 100)->nullable();
            $table->date('target_ship_date')->nullable();
            $table->date('target_approval_date')->nullable();
            $table->date('target_production_date')->nullable();
            $table->date('development_start_date')->nullable();
            $table->string('revision_no', 20)->default('REV 01');
            $table->string('status', 50)->default('DEVELOPMENT');
            $table->integer('overall_progress')->default(15);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('styles');
    }
};

