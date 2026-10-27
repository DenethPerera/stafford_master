<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Color;
use App\Models\Scale;
use App\Models\Size;
use App\Models\Style;
use App\Models\StyleBomItem;
use App\Models\StyleColorway;
use App\Models\StyleCosting;
use App\Models\StyleFile;
use App\Models\StyleSample;
use App\Models\StyleTechPack;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class StaffordDataBridgeSeeder extends Seeder
{
    public function run(): void
    {
        $this->command?->info('1. Bridging Clients from salescustomerlist...');
        $this->bridgeClients();

        $this->command?->info('2. Bridging Material Categories and Units...');
        $this->bridgeMaterials();

        $this->command?->info('3. Bridging Scales & Sizes from tbl_size_sequence...');
        $this->bridgeScalesAndSizes();

        $this->command?->info('4. Bridging Colors from tbl_style_colour...');
        $this->bridgeColors();

        $this->command?->info('5. Bridging Styles from prodstyle_list...');
        $this->bridgeStyles();

        $this->command?->info('6. Bridging BOM Items from prodstyle_cost...');
        $this->bridgeBomItems();

        $this->command?->info('7. Bridging Samples from vendorsample_list...');
        $this->bridgeSamples();

        $this->command?->info('8. Bridging Style Documents from prodstyle_docs...');
        $this->bridgeDocs();

        $this->command?->info('9. Bridging Tech Packs & Initial Costings...');
        $this->bridgeTechPacksAndCostings();

        $this->command?->info('10. Setting up PLM Menus & User Privileges...');
        $this->setupMenusAndPrivileges();

        $this->command?->info('🎉 Stafford PLM Data Bridge completed successfully!');
    }

    protected function bridgeClients(): void
    {
        if (!Schema::hasTable('salescustomerlist')) {
            return;
        }

        $customers = DB::table('salescustomerlist')->get();
        foreach ($customers as $c) {
            DB::table('clients')->updateOrInsert(
                ['id' => $c->customer_id],
                [
                    'name'           => $c->customer_name ?: ('Client ' . $c->customer_id),
                    'code'           => $c->customer_ref ?: ('CUS-' . $c->customer_id),
                    'email'          => $c->customer_email ?: null,
                    'phone'          => $c->contact_no ?: null,
                    'address'        => $c->customer_addr ?: null,
                    'contact_person' => $c->buyer_contact_name ?: null,
                    'status'         => 'Active',
                    'notes'          => $c->cust_remarks ?: null,
                    'created_at'     => $c->created_at ?: now(),
                    'updated_at'     => $c->updated_at ?: now(),
                ]
            );
        }
    }

    protected function bridgeMaterials(): void
    {
        if (Schema::hasTable('mrmcategory') && Schema::hasTable('material_categories')) {
            $categories = DB::table('mrmcategory')->get();
            foreach ($categories as $cat) {
                DB::table('material_categories')->updateOrInsert(
                    ['id' => $cat->catcode],
                    [
                        'name'        => trim($cat->catname),
                        'code'        => 'CAT-' . $cat->catcode,
                        'description' => 'Stafford Category ' . trim($cat->catname),
                        'status'      => $cat->qrycancel == 0 ? 'active' : 'inactive',
                        'created_at'  => now(),
                        'updated_at'  => now(),
                    ]
                );
            }
        }

        if (Schema::hasTable('muomlist') && Schema::hasTable('material_units')) {
            $units = DB::table('muomlist')->get();
            foreach ($units as $u) {
                DB::table('material_units')->updateOrInsert(
                    ['id' => $u->id],
                    [
                        'unit_name'   => $u->uom_name,
                        'unit_symbol' => $u->uom_code,
                        'status'      => $u->qrycancel == 0 ? 'Active' : 'Inactive',
                        'created_at'  => now(),
                        'updated_at'  => now(),
                    ]
                );
            }
        }
    }

    protected function bridgeScalesAndSizes(): void
    {
        if (!Schema::hasTable('tbl_size_sequence')) {
            return;
        }

        $scaleNames = [
            'S01' => 'Alpha Standard (XXS - 8XL)',
            'S02' => 'Tall Series (LT - 8XLT)',
            'S03' => 'Waist x Inseam (28x30 - 58x37)',
            'S04' => 'Short / Regular / Long',
            'S05' => 'Numeric Sizes (2 - 60)',
            'S06' => 'Numeric with Inseam',
            'S07' => 'One Size / Accessories',
            'S08' => 'Numeric Length Sizes',
            'S09' => 'Dual Sizes (S-M, L-XL)',
            'S10' => 'Plus Sizes (1X - 4X)',
        ];

        $groups = DB::table('tbl_size_sequence')->select('size_group')->distinct()->pluck('size_group');

        foreach ($groups as $grp) {
            $name = $scaleNames[$grp] ?? ('Scale ' . $grp);
            Scale::updateOrCreate(
                ['name' => $name],
                ['percentage_value' => 100.00, 'status' => 'Active']
            );
        }

        $rawSizes = DB::table('tbl_size_sequence')->orderBy('size_seqnum')->get();
        $order = 1;
        foreach ($rawSizes as $rs) {
            $code = trim($rs->size_code);
            if (empty($code)) continue;

            Size::updateOrCreate(
                ['name' => $code],
                [
                    'code'       => $code,
                    'sort_order' => $rs->size_seqnum ?: $order++,
                    'status'     => 'Active',
                ]
            );
        }
    }

    protected function bridgeColors(): void
    {
        if (!Schema::hasTable('tbl_style_colour')) {
            return;
        }

        $rawColors = DB::table('tbl_style_colour')
            ->select('colour', 'colourcode')
            ->distinct()
            ->whereNotNull('colour')
            ->where('colour', '!=', '')
            ->get();

        foreach ($rawColors as $rc) {
            $name = trim($rc->colour);
            $code = trim($rc->colourcode ?: '');
            if (empty($name)) continue;

            Color::updateOrCreate(
                ['name' => $name],
                [
                    'code'     => $code ?: strtoupper(substr(preg_replace('/[^a-zA-Z0-9]/', '', $name), 0, 4)),
                    'hex_code' => '#1e293b',
                    'status'   => 'Active',
                ]
            );
        }
    }

    protected function bridgeStyles(): void
    {
        if (!Schema::hasTable('prodstyle_list')) {
            return;
        }

        $prodStyles = DB::table('prodstyle_list')->get();
        $this->command?->info("Importing/Updating {$prodStyles->count()} styles from prodstyle_list into styles table...");

        $inserted = 0;
        foreach ($prodStyles as $ps) {
            $styleNo = trim($ps->styleid);
            if (empty($styleNo)) continue;

            $clientId = DB::table('clients')->where('id', $ps->customer_id)->value('id');
            if (!$clientId) {
                $clientId = DB::table('clients')->value('id');
            }

            $season = null;
            if (!empty($ps->product_year)) {
                $season = 'Season ' . $ps->product_year;
            }

            $style = Style::updateOrCreate(
                ['style_no' => $styleNo],
                [
                    'style_name'             => $ps->product_description ?: $styleNo,
                    'design_no'              => $styleNo,
                    'client_id'              => $clientId,
                    'season'                 => $season ?: 'All Seasons',
                    'program'                => $ps->program_title ?: null,
                    'category'               => 'Apparel',
                    'size_range'             => $ps->prod_sizedesc ?: 'XS - 6XL',
                    'hts_code'               => $ps->prod_hscode ?: null,
                    'unit'                   => 'Inch',
                    'content'                => $ps->fabric_content ?: null,
                    'packing_instructions'   => $ps->pack_instruct_note ?: null,
                    'revision_no'            => $ps->product_rev ?: 'REV 01',
                    'description'            => $ps->product_description ?: $styleNo,
                    'status'                 => 'DEVELOPMENT',
                    'overall_progress'       => 25,
                    'created_at'             => $ps->created_at ?: now(),
                    'updated_at'             => $ps->updated_at ?: now(),
                ]
            );

            // Populate initial colorways if available in tbl_style_colour
            if (Schema::hasTable('tbl_style_colour')) {
                $styleColors = DB::table('tbl_style_colour')->where('styleid', $styleNo)->get();
                foreach ($styleColors as $sc) {
                    StyleColorway::firstOrCreate(
                        [
                            'style_id'   => $style->id,
                            'color_name' => trim($sc->colour),
                        ],
                        [
                            'pantone_code'     => $sc->colourcode ?: null,
                            'swatch_color_hex' => '#3b82f6',
                            'status'           => 'Approved',
                        ]
                    );
                }
            }

            $inserted++;
        }

        $this->command?->info("Imported {$inserted} styles into PLM styles table.");
    }

    protected function bridgeBomItems(): void
    {
        if (!Schema::hasTable('prodstyle_cost') || !Schema::hasTable('style_bom_items')) {
            return;
        }

        $styleMap = DB::table('styles')->pluck('id', 'style_no');
        $prodStyleToStyleNo = DB::table('prodstyle_list')->pluck('styleid', 'id');

        $costs = DB::table('prodstyle_cost')
            ->leftJoin('mrmitem', 'mrmitem.itemno', '=', 'prodstyle_cost.itemno')
            ->leftJoin('mrmsupplier', 'mrmsupplier.supplierno', '=', 'prodstyle_cost.supplierno')
            ->leftJoin('mrmcategory', 'mrmcategory.catcode', '=', 'mrmitem.catcode')
            ->select(
                'prodstyle_cost.*',
                'mrmitem.itemcode',
                'mrmitem.itemdesc',
                'mrmitem.item_uom as item_unit',
                'mrmsupplier.supname',
                'mrmcategory.catname'
            )
            ->get();

        $this->command?->info("Bridging {$costs->count()} BOM cost lines into style_bom_items...");

        DB::table('style_bom_items')->delete();
        $batch = [];
        $order = 0;
        foreach ($costs as $c) {
            $styleNo = $prodStyleToStyleNo[$c->prodstyle_list_id] ?? null;
            $styleId = $styleNo ? ($styleMap[$styleNo] ?? null) : null;
            if (!$styleId) continue;

            $itemDesc = trim($c->itemdesc ?: ($c->itemcode ?: 'Raw Material Item'));
            $compType = trim($c->catname ?: 'Trim');
            $uom = trim($c->item_uom ?: ($c->item_unit ?: 'Pcs'));
            $unitCost = (float) ($c->item_supprice ?: 0);
            $supplier = trim($c->supname ?: '');
            $placement = trim($c->itemplacement ?: '');

            $batch[] = [
                'style_id'         => $styleId,
                'component_type'   => $compType,
                'item_description' => substr($itemDesc, 0, 250),
                'supplier'         => substr($supplier, 0, 150),
                'placement'        => substr($placement, 0, 150),
                'color'            => null,
                'qty_per_size'     => 1,
                'uom'              => substr($uom, 0, 20),
                'unit_cost'        => $unitCost,
                'total_cost'       => $unitCost,
                'sort_order'       => ++$order,
                'created_at'       => $c->created_at ?: now(),
                'updated_at'       => $c->updated_at ?: now(),
            ];

            if (count($batch) >= 500) {
                DB::table('style_bom_items')->insert($batch);
                $batch = [];
            }
        }

        if (!empty($batch)) {
            DB::table('style_bom_items')->insert($batch);
        }

        $this->command?->info("BOM Items bridging completed.");
    }

    protected function bridgeSamples(): void
    {
        if (!Schema::hasTable('vendorsample_list') || !Schema::hasTable('style_samples')) {
            return;
        }

        $styleMap = DB::table('styles')->pluck('id', 'style_no');
        $prodStyleToStyleNo = DB::table('prodstyle_list')->pluck('styleid', 'id');

        $samples = DB::table('vendorsample_list')->get();
        $this->command?->info("Bridging {$samples->count()} samples into style_samples...");

        DB::table('style_samples')->delete();
        $batch = [];
        $order = 0;
        foreach ($samples as $s) {
            $styleNo = $prodStyleToStyleNo[$s->prodstyle_list_id] ?? null;
            $styleId = $styleNo ? ($styleMap[$styleNo] ?? null) : null;
            if (!$styleId) continue;

            $status = (stripos($s->customer_comment ?? '', 'approv') !== false) ? 'Approved' : 'Pending';
            $sentDate = ($s->act_prep_date && $s->act_prep_date !== '0000-00-00') ? $s->act_prep_date : null;

            $batch[] = [
                'style_id'      => $styleId,
                'sample_name'   => trim($s->prod_item ?: ('Sample #' . $s->id)),
                'sample_code'   => trim($s->br_code ?: ('SMP-' . $s->id)),
                'sent_date'     => $sentDate,
                'status'        => $status,
                'submitted_by'  => $s->created_by ?: 'System',
                'approval_date' => $status === 'Approved' ? ($s->updated_at ? date('Y-m-d', strtotime($s->updated_at)) : null) : null,
                'remarks'       => $s->customer_comment ?: null,
                'sample_image'  => null,
                'sort_order'    => ++$order,
                'created_at'    => $s->created_at ?: now(),
                'updated_at'    => $s->updated_at ?: now(),
            ];

            if (count($batch) >= 500) {
                DB::table('style_samples')->insert($batch);
                $batch = [];
            }
        }

        if (!empty($batch)) {
            DB::table('style_samples')->insert($batch);
        }

        $this->command?->info("Samples bridging completed.");
    }

    protected function bridgeDocs(): void
    {
        if (!Schema::hasTable('prodstyle_docs') || !Schema::hasTable('style_files')) {
            return;
        }

        $styleMap = DB::table('styles')->pluck('id', 'style_no');
        $prodStyleToStyleNo = DB::table('prodstyle_list')->pluck('styleid', 'id');

        $docsQuery = DB::table('prodstyle_docs');
        if (Schema::hasTable('prodstyle_doc_types')) {
            $docsQuery->leftJoin('prodstyle_doc_types', 'prodstyle_doc_types.id', '=', 'prodstyle_docs.prodstyle_doc_type_id')
                ->select('prodstyle_docs.*', 'prodstyle_doc_types.doc_type_name');
        }
        $docs = $docsQuery->get();

        $this->command?->info("Bridging {$docs->count()} style docs into style_files...");

        DB::table('style_files')->delete();
        $batch = [];
        foreach ($docs as $d) {
            $styleNo = $prodStyleToStyleNo[$d->prodstyle_list_id] ?? null;
            $styleId = $styleNo ? ($styleMap[$styleNo] ?? null) : null;
            if (!$styleId) continue;

            $ext = strtolower($d->file_realext ?: 'pdf');
            $fullName = ($d->file_name ? $d->file_name : 'Document_' . $d->id) . '.' . $ext;

            $batch[] = [
                'style_id'    => $styleId,
                'file_name'   => substr($fullName, 0, 250),
                'file_type'   => strtoupper($ext),
                'file_size'   => '1.5 MB',
                'folder'      => $d->doc_type_name ?: 'Tech Pack',
                'file_path'   => $d->file_key . '_' . $d->id . '.' . $ext,
                'uploaded_by' => $d->repupload_by ?: 'admin',
                'created_at'  => $d->repupload_time ?: now(),
                'updated_at'  => $d->repupload_time ?: now(),
            ];

            if (count($batch) >= 500) {
                DB::table('style_files')->insert($batch);
                $batch = [];
            }
        }

        if (!empty($batch)) {
            DB::table('style_files')->insert($batch);
        }

        $this->command?->info("Style Docs bridging completed.");
    }

    protected function bridgeTechPacksAndCostings(): void
    {
        $allStyles = DB::table('styles')->get();

        foreach ($allStyles as $st) {
            // Tech Pack Initial Record
            if (Schema::hasTable('style_tech_packs')) {
                StyleTechPack::firstOrCreate(
                    ['style_id' => $st->id],
                    [
                        'fitting'           => 'Regular Fit',
                        'stitching'         => 'Single needle lockstitch, 10-12 SPI',
                        'label_branding'    => 'Woven Main Label at Inside Back Neck',
                        'care_instructions' => 'Machine wash cold with like colors, tumble dry low',
                        'packaging_notes'   => $st->packing_instructions ?: 'Individual polybag, standard master carton',
                        'drawcord_notes'    => 'Standard match drawcord',
                        'seam_notes'        => 'Double needle topstitch',
                        'thread_notes'      => '100% Spun Poly Thread',
                        'remarks'           => 'Stafford PLM Tech Pack Spec',
                    ]
                );
            }

            // Costing Initial Record
            if (Schema::hasTable('style_costings')) {
                $totalBomCost = DB::table('style_bom_items')->where('style_id', $st->id)->sum('total_cost');

                StyleCosting::firstOrCreate(
                    ['style_id' => $st->id],
                    [
                        'currency'          => 'USD',
                        'overhead_percent'  => 10.00,
                        'overhead_amount'   => round($totalBomCost * 0.10, 4),
                        'profit_percent'    => 15.00,
                        'profit_amount'     => round($totalBomCost * 0.15, 4),
                        'total_direct_cost' => round($totalBomCost, 4),
                        'total_cost'        => round($totalBomCost * 1.25, 4),
                        'target_price'      => round($totalBomCost * 1.35, 4),
                    ]
                );
            }
        }
    }

    protected function setupMenusAndPrivileges(): void
    {
        if (!Schema::hasTable('tbl_menu_list')) {
            return;
        }

        $menus = [
            ['idtbl_menu_list' => 1,  'menu' => 'Privileges',              'status' => 1],
            ['idtbl_menu_list' => 2,  'menu' => 'User Types',              'status' => 1],
            ['idtbl_menu_list' => 3,  'menu' => 'User Accounts',           'status' => 1],
            ['idtbl_menu_list' => 10, 'menu' => 'Dashboard',               'status' => 1],
            ['idtbl_menu_list' => 11, 'menu' => 'Styles Workspace (PLM)',  'status' => 1],
            ['idtbl_menu_list' => 21, 'menu' => 'Scales Master',           'status' => 1],
            ['idtbl_menu_list' => 22, 'menu' => 'Colors Master',           'status' => 1],
            ['idtbl_menu_list' => 23, 'menu' => 'Sizes Master',            'status' => 1],
            ['idtbl_menu_list' => 24, 'menu' => 'Design Positions Master', 'status' => 1],
            ['idtbl_menu_list' => 25, 'menu' => 'Clients Master',          'status' => 1],
            ['idtbl_menu_list' => 30, 'menu' => 'Activity Logs',           'status' => 1],
        ];

        foreach ($menus as $m) {
            DB::table('tbl_menu_list')->updateOrInsert(
                ['idtbl_menu_list' => $m['idtbl_menu_list']],
                $m
            );
        }

        // Grant full access to all users in tbl_user_privilege
        if (Schema::hasTable('tbl_user_privilege')) {
            $allUsers = DB::table('users')->get();
            $allMenus = DB::table('tbl_menu_list')->get();

            foreach ($allUsers as $u) {
                foreach ($allMenus as $m) {
                    DB::table('tbl_user_privilege')->updateOrInsert(
                        [
                            'tbl_user_idtbl_user'           => $u->id,
                            'tbl_menu_list_idtbl_menu_list' => $m->idtbl_menu_list,
                        ],
                        [
                            'access_status'  => 1,
                            'add'            => 1,
                            'edit'           => 1,
                            'statuschange'   => 1,
                            'remove'         => 1,
                            'status'         => 1,
                            'approvestatus'  => 1,
                            'checkstatus'    => 1,
                            'updatedatetime' => now(),
                        ]
                    );
                }
            }
        }
    }
}