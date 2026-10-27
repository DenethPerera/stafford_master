<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Style;
use App\Models\StyleArtwork;
use App\Models\StyleBomItem;
use App\Models\StyleColorway;
use App\Models\StyleComment;
use App\Models\StyleCosting;
use App\Models\StyleFile;
use App\Models\StyleImage;
use App\Models\StyleMeasurement;
use App\Models\StyleRevision;
use App\Models\StyleSample;
use App\Models\StyleTechPack;
use Illuminate\Database\Seeder;

class StylePlmSeeder extends Seeder
{
    public function run(): void
    {
        $client = Client::firstOrCreate(
            ['name' => 'AlphaBroder Inc.'],
            ['phone' => '1-800-523-4585', 'address' => '600 Washington Ave, Trevose, PA', 'status' => 'Active']
        );

        $style = Style::updateOrCreate(
            ['style_no' => 'TT96'],
            [
                'design_no'              => 'TT96',
                'style_name'             => 'Zone Hydrosport Heavyweight Hooded Sweatshirt',
                'client_id'              => $client->id,
                'season'                 => 'Spring 2022',
                'program'                => 'ZONE HYDROSPORT',
                'category'               => "Men's Sweatshirts",
                'gender'                 => 'Men',
                'size_range'             => 'XS - 6XL',
                'hts_code'               => '6110.20.90',
                'unit'                   => 'Inch',
                'content'                => '80% Cotton 20% Polyester Fleece',
                'gsm'                    => '330 GSM',
                'construction'           => 'Fleece',
                'gauge'                  => '12GG',
                'sleeve'                 => 'Set-in',
                'hood'                   => '3 Panel Hood with Drawcord',
                'pocket'                 => 'Kangaroo Pocket',
                'description'            => 'Zone Hydrosport Heavyweight Hooded Sweatshirt with premium fleece, ribbed cuffs, kangaroo pocket, and drawcord hood.',
                'tags'                   => ['Hoodie', 'Fleece', 'Active'],
                'packing_instructions'   => 'Polybag each. 24 pcs / carton. Carton size: 60x40x40 cm. Gross weight: 12.5 kg',
                'special_instructions'   => 'Heat transfer logo at chest. Hangtag attached.',
                'ship_to'                => 'AlphaBroder Inc. - Main DC',
                'country'                => 'USA',
                'port'                   => 'Los Angeles',
                'target_ship_date'       => '2026-11-15',
                'target_approval_date'   => '2026-09-30',
                'target_production_date' => '2026-10-15',
                'development_start_date' => '2026-06-03',
                'revision_no'            => 'REV 03',
                'status'                 => 'DEVELOPMENT',
                'overall_progress'       => 60,
            ]
        );

        // Tech Pack
        StyleTechPack::updateOrCreate(
            ['style_id' => $style->id],
            [
                'fitting'           => 'Regular Fit',
                'stitching'         => '3 Panel Hood with Drawcord',
                'label_branding'    => 'Main Label at Neck, Care Label at Side Seam',
                'care_instructions' => 'Machine wash cold inside out, Tumble dry low',
                'packaging_notes'   => 'Polybag each, 24 pcs / carton',
                'drawcord_notes'    => 'Round Drawcord with Metal Tip',
                'seam_notes'        => 'Coverstitch on Armhole, Pocket & Bottom',
                'thread_notes'      => 'Matching Body Color',
                'remarks'           => 'Follow approved sample for construction',
            ]
        );

        // BOM Items
        $bomList = [
            ['component_type' => 'Fabric', 'item_description' => 'FBR-2456 - 330 GSM Fleece', 'supplier' => 'S-Kumaras Textiles', 'placement' => 'Body', 'color' => 'Navy', 'qty_per_size' => 1.05, 'uom' => 'Mtr', 'unit_cost' => 3.20, 'total_cost' => 3.36],
            ['component_type' => 'Rib', 'item_description' => 'RIB-1320 - 2x2 Rib', 'supplier' => 'S-Kumaras Textiles', 'placement' => 'Cuff, Bottom', 'color' => 'Navy', 'qty_per_size' => 0.45, 'uom' => 'Mtr', 'unit_cost' => 2.10, 'total_cost' => 0.95],
            ['component_type' => 'Drawcord', 'item_description' => 'DRW-108 - Round Drawcord', 'supplier' => 'Maxim India', 'placement' => 'Hood', 'color' => 'Navy', 'qty_per_size' => 1.20, 'uom' => 'Mtr', 'unit_cost' => 0.35, 'total_cost' => 0.42],
            ['component_type' => 'Trims', 'item_description' => 'EYE-17 - Metal Eyelet', 'supplier' => 'Maxim India', 'placement' => 'Hood', 'color' => 'Black', 'qty_per_size' => 2.00, 'uom' => 'Pcs', 'unit_cost' => 0.05, 'total_cost' => 0.10],
            ['component_type' => 'Trims', 'item_description' => 'TIP-01 - Metal Tip', 'supplier' => 'Maxim India', 'placement' => 'Drawcord End', 'color' => 'Black', 'qty_per_size' => 2.00, 'uom' => 'Pcs', 'unit_cost' => 0.08, 'total_cost' => 0.16],
            ['component_type' => 'Label', 'item_description' => 'LBL-CL-01 - Main Label', 'supplier' => 'ITL Labels', 'placement' => 'Neck', 'color' => 'White', 'qty_per_size' => 1.00, 'uom' => 'Pcs', 'unit_cost' => 0.12, 'total_cost' => 0.12],
            ['component_type' => 'Label', 'item_description' => 'LBL-CL-05 - Care Label', 'supplier' => 'ITL Labels', 'placement' => 'Side Seam', 'color' => 'White', 'qty_per_size' => 1.00, 'uom' => 'Pcs', 'unit_cost' => 0.08, 'total_cost' => 0.08],
        ];

        foreach ($bomList as $i => $item) {
            StyleBomItem::firstOrCreate(
                ['style_id' => $style->id, 'item_description' => $item['item_description']],
                array_merge($item, ['sort_order' => $i + 1])
            );
        }

        // Measurements
        $measurements = [
            ['measurement_point' => 'Chest - 1" below armhole', 'spec_value' => 21.0, 'tol_minus' => 0.5, 'tol_plus' => 0.5, 'xs' => 19.0, 's' => 20.0, 'm' => 21.0, 'l' => 22.0, 'xl' => 23.5, 'xxl' => 25.0, '3xl' => 26.5, '4xl' => 28.0, '5xl' => 29.5, '6xl' => 31.0, 'uom' => 'Inch'],
            ['measurement_point' => 'Body length from HPS', 'spec_value' => 28.0, 'tol_minus' => 0.5, 'tol_plus' => 0.5, 'xs' => 26.0, 's' => 27.0, 'm' => 28.0, 'l' => 29.0, 'xl' => 30.0, 'xxl' => 31.0, '3xl' => 32.0, '4xl' => 33.0, '5xl' => 34.0, '6xl' => 35.0, 'uom' => 'Inch'],
            ['measurement_point' => 'Sleeve length from CB', 'spec_value' => 35.5, 'tol_minus' => 0.5, 'tol_plus' => 0.5, 'xs' => 33.5, 's' => 34.5, 'm' => 35.5, 'l' => 36.5, 'xl' => 37.5, 'xxl' => 38.5, '3xl' => 39.5, '4xl' => 40.5, '5xl' => 41.5, '6xl' => 42.5, 'uom' => 'Inch'],
            ['measurement_point' => 'Shoulder width', 'spec_value' => 17.5, 'tol_minus' => 0.3, 'tol_plus' => 0.3, 'xs' => 15.5, 's' => 16.5, 'm' => 17.5, 'l' => 18.5, 'xl' => 19.5, 'xxl' => 20.5, '3xl' => 21.5, '4xl' => 22.5, '5xl' => 23.5, '6xl' => 24.5, 'uom' => 'Inch'],
            ['measurement_point' => 'Across front - 5" below HPS', 'spec_value' => 16.5, 'tol_minus' => 0.3, 'tol_plus' => 0.3, 'xs' => 14.5, 's' => 15.5, 'm' => 16.5, 'l' => 17.5, 'xl' => 18.5, 'xxl' => 19.5, '3xl' => 20.5, '4xl' => 21.5, '5xl' => 22.5, '6xl' => 23.5, 'uom' => 'Inch'],
            ['measurement_point' => 'Bottom opening - relaxed', 'spec_value' => 18.0, 'tol_minus' => 0.5, 'tol_plus' => 0.5, 'xs' => 16.0, 's' => 17.0, 'm' => 18.0, 'l' => 19.0, 'xl' => 20.0, 'xxl' => 21.0, '3xl' => 22.0, '4xl' => 23.0, '5xl' => 24.0, '6xl' => 25.0, 'uom' => 'Inch'],
            ['measurement_point' => 'Sleeve opening - relaxed', 'spec_value' => 3.8, 'tol_minus' => 0.3, 'tol_plus' => 0.3, 'xs' => 3.0, 's' => 3.5, 'm' => 3.8, 'l' => 4.0, 'xl' => 4.3, 'xxl' => 4.5, '3xl' => 4.8, '4xl' => 5.0, '5xl' => 5.3, '6xl' => 5.5, 'uom' => 'Inch'],
        ];

        foreach ($measurements as $i => $m) {
            StyleMeasurement::firstOrCreate(
                ['style_id' => $style->id, 'measurement_point' => $m['measurement_point']],
                array_merge($m, ['sort_order' => $i + 1])
            );
        }

        // Colorways
        $colors = [
            ['color_name' => 'Black', 'pantone_code' => 'PANTONE 19-4005 TCX', 'lab_op_ref' => 'Lab Op Ref: LD-001', 'swatch_color_hex' => '#18181b', 'status' => 'Approved'],
            ['color_name' => 'Dark Grey Heather', 'pantone_code' => 'PANTONE A1TC', 'lab_op_ref' => 'Lab Op Ref: LD-002', 'swatch_color_hex' => '#3f3f46', 'status' => 'Approved'],
            ['color_name' => 'Safety Yellow', 'pantone_code' => 'PANTONE 13-0647 TCX', 'lab_op_ref' => 'Lab Op Ref: LD-003', 'swatch_color_hex' => '#eab308', 'status' => 'Approved'],
            ['color_name' => 'Forest Green', 'pantone_code' => 'PANTONE 19-6024 TCX', 'lab_op_ref' => 'Lab Op Ref: LD-004', 'swatch_color_hex' => '#14532d', 'status' => 'Approved'],
            ['color_name' => 'Brown', 'pantone_code' => 'PANTONE 19-1012 TCX', 'lab_op_ref' => 'Lab Op Ref: LD-005', 'swatch_color_hex' => '#78350f', 'status' => 'Approved'],
            ['color_name' => 'Navy', 'pantone_code' => 'PANTONE 19-4027 TCX', 'lab_op_ref' => 'Lab Op Ref: LD-006', 'swatch_color_hex' => '#1e3a8a', 'status' => 'Approved'],
            ['color_name' => 'Red', 'pantone_code' => 'PANTONE 18-1663 TCX', 'lab_op_ref' => 'Lab Op Ref: LD-007', 'swatch_color_hex' => '#b91c1c', 'status' => 'Approved'],
            ['color_name' => 'Royal Blue', 'pantone_code' => 'PANTONE 19-3955 TCX', 'lab_op_ref' => 'Lab Op Ref: LD-008', 'swatch_color_hex' => '#2563eb', 'status' => 'Approved'],
        ];

        foreach ($colors as $c) {
            StyleColorway::firstOrCreate(
                ['style_id' => $style->id, 'color_name' => $c['color_name']],
                $c
            );
        }

        // Samples
        $samples = [
            ['sample_name' => 'Proto Sample', 'sample_code' => 'PYS-001', 'sent_date' => '2026-06-12', 'status' => 'Approved', 'submitted_by' => 'Mahesh G.', 'approval_date' => '2026-06-18', 'remarks' => 'Initial development sample', 'sort_order' => 1],
            ['sample_name' => 'Fit Sample 1', 'sample_code' => 'FTS-001', 'sent_date' => '2026-06-25', 'status' => 'Revised', 'submitted_by' => 'Mahesh G.', 'approval_date' => null, 'remarks' => 'Sleeve length & chest changed', 'sort_order' => 2],
            ['sample_name' => 'Fit Sample 2', 'sample_code' => 'FTS-002', 'sent_date' => '2026-07-08', 'status' => 'Approved', 'submitted_by' => 'Mahesh G.', 'approval_date' => '2026-07-10', 'remarks' => 'Customer approved', 'sort_order' => 3],
            ['sample_name' => 'PP Sample', 'sample_code' => 'PPS-001', 'sent_date' => '2026-07-22', 'status' => 'Pending', 'submitted_by' => 'Mahesh G.', 'approval_date' => null, 'remarks' => 'Awaiting customer approval', 'sort_order' => 4],
            ['sample_name' => 'TOP Sample', 'sample_code' => 'TOP-001', 'sent_date' => null, 'status' => 'Not Started', 'submitted_by' => '—', 'approval_date' => null, 'remarks' => 'Pending PP approval', 'sort_order' => 5],
        ];

        foreach ($samples as $s) {
            StyleSample::firstOrCreate(
                ['style_id' => $style->id, 'sample_name' => $s['sample_name']],
                $s
            );
        }

        // Artworks
        StyleArtwork::firstOrCreate(
            ['style_id' => $style->id, 'artwork_name' => 'Front Print - ZONE HYDROSPORT'],
            [
                'artwork_code' => 'AWFC-FR-01',
                'type'         => 'Screen Print',
                'placement'    => 'Center Chest',
                'dimensions'   => '18" W x 6.2" H',
                'colors'       => ['Navy', 'White', 'Red'],
            ]
        );

        // Costing
        StyleCosting::updateOrCreate(
            ['style_id' => $style->id],
            [
                'currency'            => 'USD',
                'material_costs'      => ['Fabric' => 3.29, 'Rib' => 2.10, 'Trims & Accessories' => 1.36, 'Labels & Packaging' => 0.45],
                'manufacturing_costs' => ['Cutting' => 0.35, 'Sewing' => 1.20, 'Finishing' => 0.40],
                'overhead_percent'    => 10.00,
                'overhead_amount'     => 1.09,
                'profit_percent'      => 15.00,
                'profit_amount'       => 1.79,
                'total_direct_cost'   => 9.15,
                'total_cost'          => 11.94,
                'target_price'        => 13.73,
            ]
        );

        // Comments
        StyleComment::firstOrCreate(
            ['style_id' => $style->id, 'comment' => 'Please increase body length by 1/2" and update artwork placement.'],
            ['is_new' => true]
        );
        StyleComment::firstOrCreate(
            ['style_id' => $style->id, 'comment' => 'Updated as requested. Please check REV 03.'],
            ['is_new' => false]
        );
        StyleComment::firstOrCreate(
            ['style_id' => $style->id, 'comment' => 'Looks good. Proceed with PP sample.'],
            ['is_new' => false]
        );

        // Revisions
        StyleRevision::firstOrCreate(
            ['style_id' => $style->id, 'revision_no' => 'REV 03'],
            ['status' => 'DEVELOPMENT', 'changes_summary' => 'Updated chest specs and artwork placement.']
        );
        StyleRevision::firstOrCreate(
            ['style_id' => $style->id, 'revision_no' => 'REV 02'],
            ['status' => 'FIT 2', 'changes_summary' => 'Customer approved fit sample 2.']
        );
        StyleRevision::firstOrCreate(
            ['style_id' => $style->id, 'revision_no' => 'REV 01'],
            ['status' => 'CREATION', 'changes_summary' => 'Initial style setup in PLM.']
        );
    }
}
