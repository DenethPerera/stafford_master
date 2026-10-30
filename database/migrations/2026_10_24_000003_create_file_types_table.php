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
        if (!Schema::hasTable('file_types')) {
            Schema::create('file_types', function (Blueprint $table) {
                $table->id();
                $table->string('name', 100)->unique();
                $table->string('code', 50)->nullable();
                $table->text('description')->nullable();
                $table->string('status', 20)->default('Active');
                $table->timestamps();
            });

            // Seed initial industry-standard file types with descriptions
            $initialTypes = [
                [
                    'name'        => 'Tech Pack Specification',
                    'code'        => 'TP-SPEC',
                    'description' => 'Complete style technical package specification sheets, construction callouts, and garment details.',
                    'status'      => 'Active',
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],
                [
                    'name'        => 'Measurement Specification',
                    'code'        => 'MEAS-SPEC',
                    'description' => 'Point-of-measure (POM) graded charts, tolerance guidelines, and grade rule tables.',
                    'status'      => 'Active',
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],
                [
                    'name'        => 'Bill of Materials (BOM)',
                    'code'        => 'BOM-DOC',
                    'description' => 'Fabric, trim, thread, and packaging bill of materials breakdown and supplier sourcing list.',
                    'status'      => 'Active',
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],
                [
                    'name'        => 'Artwork & Graphic Master',
                    'code'        => 'ART-MSTR',
                    'description' => 'Print, embroidery, badge placement guides, high-res graphic vector art, and color separations.',
                    'status'      => 'Active',
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],
                [
                    'name'        => 'Fabric & Lab Dip Card',
                    'code'        => 'LAB-DIP',
                    'description' => 'Fabric test reports, shade band swatches, lab dip color approvals, and shrinkage test results.',
                    'status'      => 'Active',
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],
                [
                    'name'        => 'Costing & Price Breakdown',
                    'code'        => 'COST-SHT',
                    'description' => 'Pre-costing, FOB price breakdown sheets, CM (cut and make) details, and consumption analysis.',
                    'status'      => 'Active',
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],
                [
                    'name'        => 'Buyer Purchase Order (PO)',
                    'code'        => 'BUYER-PO',
                    'description' => 'Buyer purchase orders, commercial contracts, delivery milestones, and shipment agreements.',
                    'status'      => 'Active',
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],
                [
                    'name'        => 'Inspection & QC Audit',
                    'code'        => 'QC-AUDIT',
                    'description' => 'Inline, mid-production, and final AQL quality inspection reports, pull test, and metal detector logs.',
                    'status'      => 'Active',
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],
                [
                    'name'        => 'Compliance & Certificate',
                    'code'        => 'COMP-CERT',
                    'description' => 'OEKO-TEX, GOTS, BSCI, WRAP, REACH, and environmental laboratory safety compliance certificates.',
                    'status'      => 'Active',
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],
                [
                    'name'        => 'Packaging & Barcode Sheet',
                    'code'        => 'PACK-BC',
                    'description' => 'Carton packing instructions, polybag warning labels, barcode tags, and retail display guidelines.',
                    'status'      => 'Active',
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],
            ];

            DB::table('file_types')->insert($initialTypes);
        }

        if (Schema::hasTable('style_files')) {
            Schema::table('style_files', function (Blueprint $table) {
                if (!Schema::hasColumn('style_files', 'file_type_id')) {
                    $table->foreignId('file_type_id')->nullable()->after('style_id')->constrained('file_types')->nullOnDelete();
                }
                if (!Schema::hasColumn('style_files', 'description')) {
                    $table->text('description')->nullable()->after('folder');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('style_files')) {
            Schema::table('style_files', function (Blueprint $table) {
                if (Schema::hasColumn('style_files', 'file_type_id')) {
                    $table->dropForeign(['file_type_id']);
                    $table->dropColumn('file_type_id');
                }
                if (Schema::hasColumn('style_files', 'description')) {
                    $table->dropColumn('description');
                }
            });
        }

        Schema::dropIfExists('file_types');
    }
};
