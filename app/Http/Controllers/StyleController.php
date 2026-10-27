<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\ArtworkType;
use App\Models\PatternFileType;
use App\Models\FileType;
use App\Models\Scale;
use App\Models\Size;
use App\Models\Style;
use App\Models\StyleApproval;
use App\Models\StyleArtwork;
use App\Models\StyleBomItem;
use App\Models\StyleColorway;
use App\Models\StyleComment;
use App\Models\StyleCosting;
use App\Models\StyleFile;
use App\Models\StyleImage;
use App\Models\StyleMeasurement;
use App\Models\StyleRevision;
use App\Models\SampleType;
use App\Models\StyleSample;
use App\Models\StyleTechPack;
use App\Models\StyleTechPackDocument;
use App\Models\TechPackType;
use App\Services\PantoneService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class StyleController extends Controller
{
    /**
     * Display a listing of styles.
     */
    public function index(Request $request): View|JsonResponse
    {
        if (Gate::has('view-styles')) {
            Gate::authorize('view-styles');
        }

        if ($request->ajax() || $request->has('draw')) {
            $styles = Style::with(['client', 'images'])
                ->select('styles.*');

            if ($request->filled('client_id')) {
                $styles->where('client_id', $request->client_id);
            }

            if ($request->filled('season_filter')) {
                $styles->where('season', $request->season_filter);
            }

            if ($request->filled('status_filter')) {
                $styles->where('status', $request->status_filter);
            }

            return DataTables::eloquent($styles)
                ->addColumn('image_thumb', function ($row) {
                    $images = $row->images;
                    $count = $images->count();

                    if ($count > 0) {
                        $primary = $images->where('is_primary', true)->first() ?: $images->first();
                        $badgeHtml = $count > 1 ? '<span class="position-absolute top-0 start-100 translate-middle badge rounded-circle bg-primary text-white fs-9 border border-white shadow-xs" style="width:18px; height:18px; padding:0; display:flex; align-items:center; justify-content:center; z-index:2;">+' . ($count - 1) . '</span>' : '';

                        return '<div class="d-flex align-items-center justify-content-center">
                                    <div class="position-relative">
                                        <img src="' . e($primary->image_url) . '" alt="' . e($row->style_no) . '" 
                                             class="rounded-3 border border-1 border-gray-200 shadow-xs preview-style-img" 
                                             style="width: 44px; height: 44px; object-fit: cover;">
                                        ' . $badgeHtml . '
                                    </div>
                                </div>';
                    }

                    return '<div class="d-flex align-items-center justify-content-center">
                                <div class="bg-light-subtle text-muted rounded-3 d-flex align-items-center justify-content-center border border-gray-300" style="width: 44px; height: 44px;">
                                    <i class="fa-solid fa-shirt fs-4 text-gray-400"></i>
                                </div>
                            </div>';
                })
                ->addColumn('client_name', function ($row) {
                    return $row->client 
                        ? '<span class="fw-semibold text-gray-800 fs-7">' . e($row->client->name) . '</span>' 
                        : '<span class="text-muted fs-8">N/A</span>';
                })
                ->addColumn('formatted_progress', function ($row) {
                    $pct = (int)($row->overall_progress ?? 10);
                    $barClass = match(true) {
                        $pct >= 100 => 'bg-success',
                        $pct >= 60 => 'bg-primary',
                        $pct >= 30 => 'bg-info',
                        default => 'bg-warning',
                    };
                    $textClass = match(true) {
                        $pct >= 100 => 'text-success',
                        $pct >= 60 => 'text-primary',
                        $pct >= 30 => 'text-info',
                        default => 'text-warning',
                    };

                    return '<div class="d-flex flex-column w-100 pe-2" style="min-width: 110px;">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="fw-bolder fs-8 ' . $textClass . '">' . $pct . '%</span>
                                    <span class="text-muted fs-9 fw-semibold">Complete</span>
                                </div>
                                <div class="progress h-6px w-100 bg-secondary rounded-pill overflow-hidden" style="background-color: #ebedf3;">
                                    <div class="progress-bar ' . $barClass . ' rounded-pill" role="progressbar" style="width: ' . $pct . '%;" aria-valuenow="' . $pct . '" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>';
                })
                ->addColumn('revision_no', function ($row) {
                    $rev = $row->revision_no ?: 'REV 01';
                    return '<div class="text-center"><span class="badge bg-light-subtle text-gray-800 border border-gray-300 fw-bold fs-8 px-2.5 py-1 rounded-2">' . e($rev) . '</span></div>';
                })
                ->addColumn('status_badge', function ($row) {
                    $status = strtoupper($row->status ?: 'DEVELOPMENT');
                    $class = match($status) {
                        'DEVELOPMENT' => 'badge-light-primary text-primary border-primary-subtle',
                        'FIT 2', 'APPROVED' => 'badge-light-success text-success border-success-subtle',
                        'REVISED', 'PENDING' => 'badge-light-warning text-warning border-warning-subtle',
                        'ARCHIVED' => 'badge-light-danger text-danger border-danger-subtle',
                        default => 'badge-light-info text-info border-info-subtle',
                    };
                    return '<div class="text-center"><span class="badge ' . $class . ' border fw-bolder px-3 py-1.5 fs-8 text-uppercase rounded-pill">' . e($status) . '</span></div>';
                })
                ->addColumn('actions', function ($row) {
                    $showUrl = route('styles.show', $row->id);
                    $editUrl = route('styles.edit', $row->id);

                    return '
                        <div class="d-inline-flex align-items-center justify-content-end gap-2 text-nowrap">
                            <a href="' . $showUrl . '" 
                               class="btn btn-sm btn-primary py-1.5 px-3 d-inline-flex align-items-center fs-8 fw-bold text-nowrap rounded-2 shadow-xs btn-workspace" 
                               data-id="' . $row->id . '"
                               title="Open PLM Workspace">
                                <i class="fa-solid fa-folder-open fs-8 me-1.5"></i> Workspace
                            </a>
                            
                            <div class="ms-1">
                                <button type="button" 
                                        class="btn btn-sm btn-icon btn-light btn-active-light-primary h-32px w-32px rounded-2 border border-gray-300 btn-style-actions-toggle" 
                                        data-kt-menu-trigger="click" 
                                        data-kt-menu-placement="bottom-end" 
                                        data-id="' . $row->id . '"
                                        title="Actions Menu">
                                    <i class="fa-solid fa-ellipsis-vertical fs-6 text-gray-700"></i>
                                </button>
                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-700 menu-state-bg-light-primary fw-semibold fs-7 w-175px py-2 shadow-md border" data-kt-menu="true">
                                    <div class="menu-item px-2">
                                        <a href="' . $showUrl . '" class="menu-link px-3 py-2 rounded-2 btn-style-action" data-action="workspace" data-id="' . $row->id . '">
                                            <span class="menu-icon me-2"><i class="fa-solid fa-folder-open text-primary fs-7"></i></span>
                                            <span class="menu-title text-gray-800 fw-bold">PLM Workspace</span>
                                        </a>
                                    </div>
                                    <div class="menu-item px-2">
                                        <a href="' . $editUrl . '" class="menu-link px-3 py-2 rounded-2 btn-style-action" data-action="edit" data-id="' . $row->id . '">
                                            <span class="menu-icon me-2"><i class="fa-solid fa-pen text-warning fs-7"></i></span>
                                            <span class="menu-title text-gray-800">Edit Style</span>
                                        </a>
                                    </div>
                                    <div class="menu-item px-2">
                                        <a href="javascript:void(0)" class="menu-link px-3 py-2 rounded-2 btn-style-action btn-duplicate-style" data-action="duplicate" data-id="' . $row->id . '" data-style-no="' . e($row->style_no) . '">
                                            <span class="menu-icon me-2"><i class="fa-solid fa-copy text-info fs-7"></i></span>
                                            <span class="menu-title text-gray-800">Duplicate</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    ';
                })
                ->rawColumns(['image_thumb', 'client_name', 'revision_no', 'formatted_progress', 'status_badge', 'actions'])
                ->make(true);
        }

        $clients = Client::where('status', 'Active')->orderBy('name')->get();
        $seasons = Schema::hasTable('seasons')
            ? DB::table('seasons')->where('status', 'Active')->orderBy('name')->pluck('name')->filter()->toArray()
            : [];

        return view('styles.index', compact('clients', 'seasons'));
    }

    /**
     * Show form to create a new PLM Style.
     */
    public function create(): View
    {
        if (Gate::has('create-styles')) {
            Gate::authorize('create-styles');
        }

        $style = new Style();
        $masterOptions = $this->getMasterOptions();

        return view('styles.create', array_merge(['style' => $style], $masterOptions));
    }

    /**
     * Store a newly created PLM Style.
     */
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        if (Gate::has('create-styles')) {
            Gate::authorize('create-styles');
        }

        $validated = $request->validate([
            'style_no'               => 'required|string|max:100|unique:styles,style_no',
            'style_name'             => 'required|string|max:255',
            'client_id'              => 'required|exists:clients,id',
            'season'                 => 'required|string|max:100',
            'program'                => 'nullable|string|max:150',
            'category'               => 'required|string|max:150',
            'gender'                 => 'nullable|string|max:50',
            'revision_no'            => 'nullable|string|max:20',
            'country'                => 'required|string|max:100',
            'hts_code'               => 'nullable|string|max:50',
            'hts_code_ca'            => 'nullable|string|max:50',
            'size_range'             => 'required|string|max:100',
            'sizes'                  => 'nullable|array',
            'sizes.*'                => 'string',
            'unit'                   => 'nullable|string|max:50',
            'content'                => 'nullable|string',
            'gsm'                    => 'nullable|string|max:50',
            'construction'           => 'nullable|string|max:100',
            'treatment'              => 'nullable|string|max:100',
            'gauge'                  => 'nullable|string|max:50',
            'sleeve'                 => 'nullable|string|max:100',
            'hood'                   => 'nullable|string|max:100',
            'pocket'                 => 'nullable|string|max:100',
            'description'            => 'required|string',
            'tags'                   => 'nullable|array',
            'packing_instructions'   => 'nullable|string',
            'special_instructions'   => 'nullable|string',
            'ship_to'                => 'nullable|string|max:255',
            'port'                   => 'nullable|string|max:100',
            'target_ship_date'       => 'nullable|date',
            'target_approval_date'   => 'nullable|date',
            'target_production_date' => 'nullable|date',
            'development_start_date' => 'nullable|date',
        ]);

        try {
            DB::beginTransaction();

            $validated['created_by'] = auth()->id();
            $validated['updated_by'] = auth()->id();
            $validated['design_no'] = $validated['style_no'];
            $validated['revision_no'] = $validated['revision_no'] ?? 'REV 01';
            $validated['status'] = 'DEVELOPMENT';
            $validated['overall_progress'] = 15;

            $style = Style::create($validated);

            // Handle Categorized Style Images upload
            $destinationPath = public_path('uploads/styles');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            $types = ['Front', 'Back', 'Details', 'Colorways'];
            if ($request->has('images')) {
                foreach ($types as $type) {
                    if ($request->hasFile("images.{$type}")) {
                        foreach ($request->file("images.{$type}") as $image) {
                            $filename = 'style_' . $style->id . '_' . strtolower($type) . '_' . time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                            $image->move($destinationPath, $filename);

                            StyleImage::create([
                                'style_id'   => $style->id,
                                'image_type' => $type,
                                'image_path' => $filename,
                                'is_primary' => ($type === 'Front' && !$style->images()->where('is_primary', true)->exists()),
                                'sort_order' => $style->images()->count(),
                            ]);
                        }
                    }
                }
            }

            // Fallback for generic style_images[]
            if ($request->hasFile('style_images')) {
                $index = $style->images()->count();
                foreach ($request->file('style_images') as $image) {
                    $filename = 'style_' . $style->id . '_' . time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                    $image->move($destinationPath, $filename);

                    StyleImage::create([
                        'style_id'   => $style->id,
                        'image_type' => $types[$index % count($types)],
                        'image_path' => $filename,
                        'is_primary' => ($index === 0 && !$style->images()->where('is_primary', true)->exists()),
                        'sort_order' => $index,
                    ]);
                    $index++;
                }
            }

            // Create initial default Tech Pack
            StyleTechPack::create([
                'style_id'          => $style->id,
                'fitting'           => 'Regular Fit',
                'stitching'         => '3 Panel Hood with Drawcord',
                'label_branding'    => 'Main Label at Neck, Care Label at Side Seam',
                'care_instructions' => 'Machine wash cold inside out, Tumble dry low',
                'packaging_notes'   => 'Polybag each, 24 pcs / carton',
                'drawcord_notes'    => 'Round Drawcord with Metal Tip',
                'seam_notes'        => 'Coverstitch on Armhole, Pocket & Bottom',
                'thread_notes'      => 'Matching Body Color',
                'remarks'           => 'Follow approved sample for construction',
            ]);

            // Seed default BOM Items
            $defaultBom = [
                ['component_type' => 'Fabric', 'item_description' => 'FBR-2456 - 330 GSM Fleece', 'supplier' => 'S-Kumaras Textiles', 'placement' => 'Body', 'color' => 'Navy', 'qty_per_size' => 1.05, 'uom' => 'Mtr', 'unit_cost' => 3.20, 'total_cost' => 3.36],
                ['component_type' => 'Rib', 'item_description' => 'RIB-1320 - 2x2 Rib', 'supplier' => 'S-Kumaras Textiles', 'placement' => 'Cuff, Bottom', 'color' => 'Navy', 'qty_per_size' => 0.45, 'uom' => 'Mtr', 'unit_cost' => 2.10, 'total_cost' => 0.95],
                ['component_type' => 'Drawcord', 'item_description' => 'DRW-108 - Round Drawcord', 'supplier' => 'Maxim India', 'placement' => 'Hood', 'color' => 'Navy', 'qty_per_size' => 1.20, 'uom' => 'Mtr', 'unit_cost' => 0.35, 'total_cost' => 0.42],
                ['component_type' => 'Trims', 'item_description' => 'EYE-17 - Metal Eyelet', 'supplier' => 'Maxim India', 'placement' => 'Hood', 'color' => 'Black', 'qty_per_size' => 2.00, 'uom' => 'Pcs', 'unit_cost' => 0.05, 'total_cost' => 0.10],
                ['component_type' => 'Trims', 'item_description' => 'TIP-01 - Metal Tip', 'supplier' => 'Maxim India', 'placement' => 'Drawcord End', 'color' => 'Black', 'qty_per_size' => 2.00, 'uom' => 'Pcs', 'unit_cost' => 0.08, 'total_cost' => 0.16],
                ['component_type' => 'Label', 'item_description' => 'LBL-CL-01 - Main Label', 'supplier' => 'ITL Labels', 'placement' => 'Neck', 'color' => 'White', 'qty_per_size' => 1.00, 'uom' => 'Pcs', 'unit_cost' => 0.12, 'total_cost' => 0.12],
                ['component_type' => 'Label', 'item_description' => 'LBL-CL-05 - Care Label', 'supplier' => 'ITL Labels', 'placement' => 'Side Seam', 'color' => 'White', 'qty_per_size' => 1.00, 'uom' => 'Pcs', 'unit_cost' => 0.08, 'total_cost' => 0.08],
            ];
            foreach ($defaultBom as $i => $item) {
                $item['style_id'] = $style->id;
                $item['sort_order'] = $i + 1;
                StyleBomItem::create($item);
            }

            // Seed default Measurements Chart
            $defaultSpecs = [
                ['measurement_point' => 'Chest - 1" below armhole', 'spec_value' => 21.0, 'tol_minus' => 0.5, 'tol_plus' => 0.5, 'xs' => 19.0, 's' => 20.0, 'm' => 21.0, 'l' => 22.0, 'xl' => 23.5, 'xxl' => 25.0, '3xl' => 26.5, '4xl' => 28.0, '5xl' => 29.5, '6xl' => 31.0, 'uom' => 'Inch'],
                ['measurement_point' => 'Body length from HPS', 'spec_value' => 28.0, 'tol_minus' => 0.5, 'tol_plus' => 0.5, 'xs' => 26.0, 's' => 27.0, 'm' => 28.0, 'l' => 29.0, 'xl' => 30.0, 'xxl' => 31.0, '3xl' => 32.0, '4xl' => 33.0, '5xl' => 34.0, '6xl' => 35.0, 'uom' => 'Inch'],
                ['measurement_point' => 'Sleeve length from CB', 'spec_value' => 35.5, 'tol_minus' => 0.5, 'tol_plus' => 0.5, 'xs' => 33.5, 's' => 34.5, 'm' => 35.5, 'l' => 36.5, 'xl' => 37.5, 'xxl' => 38.5, '3xl' => 39.5, '4xl' => 40.5, '5xl' => 41.5, '6xl' => 42.5, 'uom' => 'Inch'],
                ['measurement_point' => 'Shoulder width', 'spec_value' => 17.5, 'tol_minus' => 0.3, 'tol_plus' => 0.3, 'xs' => 15.5, 's' => 16.5, 'm' => 17.5, 'l' => 18.5, 'xl' => 19.5, 'xxl' => 20.5, '3xl' => 21.5, '4xl' => 22.5, '5xl' => 23.5, '6xl' => 24.5, 'uom' => 'Inch'],
                ['measurement_point' => 'Across front - 5" below HPS', 'spec_value' => 16.5, 'tol_minus' => 0.3, 'tol_plus' => 0.3, 'xs' => 14.5, 's' => 15.5, 'm' => 16.5, 'l' => 17.5, 'xl' => 18.5, 'xxl' => 19.5, '3xl' => 20.5, '4xl' => 21.5, '5xl' => 22.5, '6xl' => 23.5, 'uom' => 'Inch'],
                ['measurement_point' => 'Bottom opening - relaxed', 'spec_value' => 18.0, 'tol_minus' => 0.5, 'tol_plus' => 0.5, 'xs' => 16.0, 's' => 17.0, 'm' => 18.0, 'l' => 19.0, 'xl' => 20.0, 'xxl' => 21.0, '3xl' => 22.0, '4xl' => 23.0, '5xl' => 24.0, '6xl' => 25.0, 'uom' => 'Inch'],
            ];
            foreach ($defaultSpecs as $i => $spec) {
                $spec['style_id'] = $style->id;
                $spec['sort_order'] = $i + 1;
                StyleMeasurement::create($spec);
            }

            // Seed default Colorways
            $defaultColors = [
                ['color_name' => 'Black', 'pantone_code' => 'PANTONE 19-4005 TCX', 'lab_op_ref' => 'Lab Op Ref: LD-001', 'swatch_color_hex' => '#18181b', 'status' => 'Approved'],
                ['color_name' => 'Dark Grey Heather', 'pantone_code' => 'PANTONE A1TC', 'lab_op_ref' => 'Lab Op Ref: LD-002', 'swatch_color_hex' => '#3f3f46', 'status' => 'Approved'],
                ['color_name' => 'Safety Yellow', 'pantone_code' => 'PANTONE 13-0647 TCX', 'lab_op_ref' => 'Lab Op Ref: LD-003', 'swatch_color_hex' => '#eab308', 'status' => 'Approved'],
                ['color_name' => 'Forest Green', 'pantone_code' => 'PANTONE 19-6024 TCX', 'lab_op_ref' => 'Lab Op Ref: LD-004', 'swatch_color_hex' => '#14532d', 'status' => 'Approved'],
                ['color_name' => 'Navy', 'pantone_code' => 'PANTONE 19-4027 TCX', 'lab_op_ref' => 'Lab Op Ref: LD-005', 'swatch_color_hex' => '#1e3a8a', 'status' => 'Approved'],
                ['color_name' => 'Red', 'pantone_code' => 'PANTONE 18-1663 TCX', 'lab_op_ref' => 'Lab Op Ref: LD-006', 'swatch_color_hex' => '#b91c1c', 'status' => 'Approved'],
            ];
            foreach ($defaultColors as $c) {
                $c['style_id'] = $style->id;
                StyleColorway::create($c);
            }

            // Seed default Samples Timeline
            $defaultSamples = [
                ['sample_name' => 'Proto Sample', 'sample_code' => 'PYS-001', 'sent_date' => '2026-06-12', 'status' => 'Approved', 'submitted_by' => 'Mahesh G.', 'approval_date' => '2026-06-18', 'remarks' => 'Initial development sample', 'sort_order' => 1],
                ['sample_name' => 'Fit Sample 1', 'sample_code' => 'FTS-001', 'sent_date' => '2026-06-25', 'status' => 'Revised', 'submitted_by' => 'Mahesh G.', 'approval_date' => null, 'remarks' => 'Sleeve length & chest changed', 'sort_order' => 2],
                ['sample_name' => 'Fit Sample 2', 'sample_code' => 'FTS-002', 'sent_date' => '2026-07-08', 'status' => 'Approved', 'submitted_by' => 'Mahesh G.', 'approval_date' => '2026-07-10', 'remarks' => 'Customer approved', 'sort_order' => 3],
                ['sample_name' => 'PP Sample', 'sample_code' => 'PPS-001', 'sent_date' => '2026-07-22', 'status' => 'Pending', 'submitted_by' => 'Mahesh G.', 'approval_date' => null, 'remarks' => 'Awaiting customer approval', 'sort_order' => 4],
                ['sample_name' => 'TOP Sample', 'sample_code' => 'TOP-001', 'sent_date' => null, 'status' => 'Not Started', 'submitted_by' => 'â€”', 'approval_date' => null, 'remarks' => 'Pending PP approval', 'sort_order' => 5],
            ];
            foreach ($defaultSamples as $s) {
                $s['style_id'] = $style->id;
                StyleSample::create($s);
            }

            // Seed default Costing summary
            StyleCosting::create([
                'style_id'            => $style->id,
                'currency'            => 'USD',
                'material_costs'      => [
                    'Fabric'              => 3.29,
                    'Rib'                 => 2.10,
                    'Trims & Accessories' => 1.36,
                    'Labels & Packaging'  => 0.45,
                ],
                'manufacturing_costs' => [
                    'Cutting'   => 0.35,
                    'Sewing'    => 1.20,
                    'Finishing' => 0.40,
                ],
                'overhead_percent'    => 10.00,
                'overhead_amount'     => 1.09,
                'profit_percent'      => 15.00,
                'profit_amount'       => 1.79,
                'total_direct_cost'   => 9.15,
                'total_cost'          => 11.94,
                'target_price'        => 13.73,
            ]);

            // Seed initial Comment
            StyleComment::create([
                'style_id' => $style->id,
                'user_id'  => auth()->id(),
                'comment'  => 'Style master created in PLM. Initial specs and BOM seeded successfully.',
                'is_new'   => true,
            ]);

            // Seed initial Revision record
            StyleRevision::create([
                'style_id'        => $style->id,
                'revision_no'     => $style->revision_no,
                'status'          => 'DEVELOPMENT',
                'changes_summary' => 'Initial style creation & tech pack setup',
                'created_by'      => auth()->id(),
            ]);

            DB::commit();

            activity()
                ->causedBy(auth()->user())
                ->performedOn($style)
                ->withProperties(['style_no' => $style->style_no])
                ->log('PLM Style Created');

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success'  => true,
                    'message'  => "Style '{$style->style_no}' created successfully in PLM Workspace!",
                    'redirect' => route('styles.show', $style->id),
                ]);
            }

            return redirect()->route('styles.show', $style->id)->with('success', "Style '{$style->style_no}' created successfully in PLM Workspace!");
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Failed to create style: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create style: ' . $e->getMessage(),
                ], 500);
            }

            return back()->withInput()->with('error', 'Failed to create style: ' . $e->getMessage());
        }
    }

    /**
     * Display detailed Style Workspace Dashboard (10 Tabs).
     */
    public function show(Style $style): View
    {
        if (Gate::has('view-styles')) {
            Gate::authorize('view-styles');
        }

        $style->load([
            'client',
            'images',
            'techPack',
            'bomItems',
            'measurements',
            'colorways',
            'samples.sampleType',
            'samples.patternFileType',
            'artworks.artworkType',
            'costing',
            'files.fileType',
            'approvals',
            'comments.user',
            'revisions.createdByUser',
            'techPackDocuments.techPackType',
            'techPackDocuments.uploadedByUser',
        ]);

        $masterConstructions = Schema::hasTable('constructions') 
            ? DB::table('constructions')->where('status', 'Active')->orderBy('name')->pluck('name')->filter()->toArray() 
            : [];
        $dbConstructions = Style::whereNotNull('construction')->where('construction', '!=', '')->distinct()->orderBy('construction')->pluck('construction')->toArray();
        $constructions = array_values(array_unique(array_filter(array_merge($masterConstructions, $dbConstructions))));
        sort($constructions);

        $sampleTypes = Schema::hasTable('sample_types')
            ? SampleType::where('status', 'Active')->orderBy('name')->get()
            : collect();

        $seasons = Schema::hasTable('seasons')
            ? DB::table('seasons')->where('status', 'Active')->orderBy('name')->pluck('name')->filter()->toArray()
            : [];
        if (!empty($style->season) && !in_array($style->season, $seasons)) {
            $seasons[] = $style->season;
        }

        $techPackTypes = Schema::hasTable('tech_pack_types')
            ? TechPackType::where('status', 'Active')->orderBy('name')->get()
            : collect();

        $artworkTypes = Schema::hasTable('artwork_types')
            ? ArtworkType::where('status', 'Active')->orderBy('name')->get()
            : collect();

        $patternFileTypes = Schema::hasTable('pattern_file_types')
            ? PatternFileType::where('status', 'Active')->orderBy('name')->get()
            : collect();

        $fileTypes = Schema::hasTable('file_types')
            ? FileType::where('status', 'Active')->orderBy('name')->get()
            : collect();

        return view('styles.show', compact('style', 'constructions', 'sampleTypes', 'seasons', 'techPackTypes', 'artworkTypes', 'patternFileTypes', 'fileTypes'));
    }

    /**
     * Show form to edit PLM Style details.
     */
    public function edit(Style $style): View
    {
        if (Gate::has('edit-styles')) {
            Gate::authorize('edit-styles');
        }

        $style->load('images');
        $masterOptions = $this->getMasterOptions();

        return view('styles.edit', array_merge(['style' => $style], $masterOptions));
    }

    /**
     * Get dynamic master options for Style Data Entry dropdowns.
     */
    protected function getMasterOptions(): array
    {
        // 1. Clients (Foreign Key Linkage: clients.id)
        $clients = Client::where('status', 'Active')->orderBy('name')->get();
        if ($clients->isEmpty()) {
            $clients = Client::orderBy('name')->get();
        }

        // 2. Size Ranges / Scales (Master Table: scales)
        $scales = Scale::whereRaw('LOWER(status) = ?', ['active'])->orderBy('name')->get();
        if ($scales->isEmpty()) {
            $scales = Scale::orderBy('name')->get();
        }
        $dbSizeRanges = $scales->pluck('name')->map(fn($n) => trim($n))->filter()->unique()->values()->toArray();
        $sizeRanges = array_values(array_unique(array_filter($dbSizeRanges)));

        // 3. Seasons (Master Table: seasons)
        $seasons = Schema::hasTable('seasons')
            ? DB::table('seasons')->where('status', 'Active')->orderBy('name')->pluck('name')->filter()->toArray()
            : [];

        // 4. Categories (Master Table: material_categories + Dynamic DB)
        $matCatNames = [];
        if (Schema::hasTable('material_categories')) {
            $matCatNames = DB::table('material_categories')->where('status', 'active')->pluck('name')->filter()->toArray();
        }
        $dbCategories = Style::whereNotNull('category')->where('category', '!=', '')->distinct()->pluck('category')->toArray();
        $categories = array_values(array_unique(array_filter(array_merge($matCatNames, $dbCategories))));
        sort($categories);

        // 5. Units (Master Table: material_units + Dynamic DB)
        $matUnits = [];
        if (Schema::hasTable('material_units')) {
            $matUnits = DB::table('material_units')->pluck('unit_name')->filter()->toArray();
        }
        $dbUnits = Style::whereNotNull('unit')->where('unit', '!=', '')->distinct()->pluck('unit')->toArray();
        $units = array_values(array_unique(array_filter(array_merge($matUnits, $dbUnits))));
        sort($units);

        // 6. Programs (Master Table: programs + Dynamic DB)
        $masterPrograms = Schema::hasTable('programs') ? DB::table('programs')->pluck('name')->filter()->toArray() : [];
        $dbPrograms = Style::whereNotNull('program')->where('program', '!=', '')->distinct()->pluck('program')->toArray();
        $programs = array_values(array_unique(array_filter(array_merge($masterPrograms, $dbPrograms))));
        sort($programs);

        // 7. Genders (Dynamic DB)
        $genders = Style::whereNotNull('gender')->where('gender', '!=', '')->distinct()->orderBy('gender')->pluck('gender')->toArray();

        // 8. Origin Countries (Master Table: tbl_country + Dynamic DB)
        $countries = [];
        if (Schema::hasTable('tbl_country')) {
            $countries = DB::table('tbl_country')->orderBy('country')->pluck('country')->map(fn($c) => trim($c))->filter()->unique()->values()->toArray();
        }
        if (empty($countries)) {
            $countries = Style::whereNotNull('country')->where('country', '!=', '')->distinct()->orderBy('country')->pluck('country')->toArray();
        }

        // 9. Constructions (Master Table: constructions + Dynamic DB)
        $masterConstructions = Schema::hasTable('constructions') 
            ? DB::table('constructions')->where('status', 'Active')->orderBy('name')->pluck('name')->filter()->toArray() 
            : [];
        $dbConstructions = Style::whereNotNull('construction')->where('construction', '!=', '')->distinct()->orderBy('construction')->pluck('construction')->toArray();
        $constructions = array_values(array_unique(array_filter(array_merge($masterConstructions, $dbConstructions))));
        sort($constructions);

        // 10. Fabric Contents (Dynamic DB)
        $contents = Style::whereNotNull('content')->where('content', '!=', '')->distinct()->orderBy('content')->pluck('content')->toArray();

        // 11. Gauge (Dynamic DB)
        $gauges = Style::whereNotNull('gauge')->where('gauge', '!=', '')->distinct()->orderBy('gauge')->pluck('gauge')->toArray();

        // 12. Sleeve (Dynamic DB)
        $sleeves = Style::whereNotNull('sleeve')->where('sleeve', '!=', '')->distinct()->orderBy('sleeve')->pluck('sleeve')->toArray();

        // 13. Hood (Dynamic DB)
        $hoods = Style::whereNotNull('hood')->where('hood', '!=', '')->distinct()->orderBy('hood')->pluck('hood')->toArray();

        // 14. Pocket (Dynamic DB)
        $pockets = Style::whereNotNull('pocket')->where('pocket', '!=', '')->distinct()->orderBy('pocket')->pluck('pocket')->toArray();

        // 15. Garment Sizes (Master Table: sizes)
        $allSizes = Size::whereRaw('LOWER(status) = ?', ['active'])->orderBy('sort_order')->orderBy('id')->get();
        if ($allSizes->isEmpty()) {
            $allSizes = Size::orderBy('sort_order')->orderBy('id')->get();
        }

        return compact(
            'clients',
            'scales',
            'sizeRanges',
            'allSizes',
            'seasons',
            'categories',
            'units',
            'programs',
            'genders',
            'countries',
            'constructions',
            'contents',
            'gauges',
            'sleeves',
            'hoods',
            'pockets'
        );
    }

    /**
     * Update PLM Style details.
     */
    public function update(Request $request, Style $style): RedirectResponse|JsonResponse
    {
        if (Gate::has('edit-styles')) {
            Gate::authorize('edit-styles');
        }

        $validated = $request->validate([
            'style_no'               => 'required|string|max:100|unique:styles,style_no,' . $style->id,
            'style_name'             => 'required|string|max:255',
            'client_id'              => 'required|exists:clients,id',
            'season'                 => 'required|string|max:100',
            'program'                => 'nullable|string|max:150',
            'category'               => 'required|string|max:150',
            'gender'                 => 'nullable|string|max:50',
            'revision_no'            => 'nullable|string|max:20',
            'country'                => 'required|string|max:100',
            'hts_code'               => 'nullable|string|max:50',
            'hts_code_ca'            => 'nullable|string|max:50',
            'size_range'             => 'required|string|max:100',
            'sizes'                  => 'nullable|array',
            'sizes.*'                => 'string',
            'unit'                   => 'nullable|string|max:50',
            'content'                => 'nullable|string',
            'gsm'                    => 'nullable|string|max:50',
            'construction'           => 'nullable|string|max:100',
            'treatment'              => 'nullable|string|max:100',
            'gauge'                  => 'nullable|string|max:50',
            'sleeve'                 => 'nullable|string|max:100',
            'hood'                   => 'nullable|string|max:100',
            'pocket'                 => 'nullable|string|max:100',
            'description'            => 'required|string',
            'tags'                   => 'nullable|array',
            'packing_instructions'   => 'nullable|string',
            'special_instructions'   => 'nullable|string',
            'ship_to'                => 'nullable|string|max:255',
            'port'                   => 'nullable|string|max:100',
            'target_ship_date'       => 'nullable|date',
            'target_approval_date'   => 'nullable|date',
            'target_production_date' => 'nullable|date',
            'development_start_date' => 'nullable|date',
            'status'                 => 'nullable|string|max:50',
        ]);

        try {
            $validated['updated_by'] = auth()->id();
            $style->update($validated);

            // Handle Categorized Style Images upload
            $destinationPath = public_path('uploads/styles');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            $types = ['Front', 'Back', 'Details', 'Colorways'];
            if ($request->has('images')) {
                foreach ($types as $type) {
                    if ($request->hasFile("images.{$type}")) {
                        foreach ($request->file("images.{$type}") as $image) {
                            $filename = 'style_' . $style->id . '_' . strtolower($type) . '_' . time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                            $image->move($destinationPath, $filename);

                            StyleImage::create([
                                'style_id'   => $style->id,
                                'image_type' => $type,
                                'image_path' => $filename,
                                'is_primary' => ($type === 'Front' && !$style->images()->where('is_primary', true)->exists()),
                                'sort_order' => $style->images()->count(),
                            ]);
                        }
                    }
                }
            }

            // Fallback for generic style_images[] if provided
            if ($request->hasFile('style_images')) {
                $index = $style->images()->count();
                foreach ($request->file('style_images') as $image) {
                    $filename = 'style_' . $style->id . '_' . time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                    $image->move($destinationPath, $filename);

                    StyleImage::create([
                        'style_id'   => $style->id,
                        'image_type' => $types[$index % count($types)],
                        'image_path' => $filename,
                        'is_primary' => ($index === 0 && !$style->images()->where('is_primary', true)->exists()),
                        'sort_order' => $index,
                    ]);
                    $index++;
                }
            }

            activity()
                ->causedBy(auth()->user())
                ->performedOn($style)
                ->log("PLM Style '{$style->style_no}' Updated");

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success'  => true,
                    'message'  => "Style '{$style->style_no}' updated successfully!",
                    'redirect' => route('styles.show', $style->id),
                ]);
            }

            return redirect()->route('styles.show', $style->id)->with('success', "Style '{$style->style_no}' updated successfully!");
        } catch (Exception $e) {
            Log::error('Failed to update style: ' . $e->getMessage());

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Duplicate a Style into a new REV 01 draft.
     */
    public function duplicate(Style $style): RedirectResponse|JsonResponse
    {
        try {
            DB::beginTransaction();

            $newStyleNo = $style->style_no . '-COPY-' . strtoupper(substr(uniqid(), -3));

            $newStyle = $style->replicate([
                'created_at',
                'updated_at',
                'deleted_at',
            ]);
            $newStyle->style_no = $newStyleNo;
            $newStyle->style_name = $style->style_name . ' (Copy)';
            $newStyle->revision_no = 'REV 01';
            $newStyle->status = 'DEVELOPMENT';
            $newStyle->overall_progress = 10;
            $newStyle->created_by = auth()->id();
            $newStyle->updated_by = auth()->id();
            $newStyle->save();

            // Replicate Tech Pack
            if ($style->techPack) {
                $techPack = $style->techPack->replicate();
                $techPack->style_id = $newStyle->id;
                $techPack->save();
            }

            // Replicate BOM
            foreach ($style->bomItems as $item) {
                $newItem = $item->replicate();
                $newItem->style_id = $newStyle->id;
                $newItem->save();
            }

            // Replicate Measurements
            foreach ($style->measurements as $m) {
                $newM = $m->replicate();
                $newM->style_id = $newStyle->id;
                $newM->save();
            }

            // Replicate Colorways
            foreach ($style->colorways as $c) {
                $newC = $c->replicate();
                $newC->style_id = $newStyle->id;
                $newC->save();
            }

            // Replicate Artworks
            foreach ($style->artworks as $a) {
                $newA = $a->replicate();
                $newA->style_id = $newStyle->id;
                $newA->save();
            }

            // Replicate Costing
            if ($style->costing) {
                $costing = $style->costing->replicate();
                $costing->style_id = $newStyle->id;
                $costing->save();
            }

            DB::commit();

            return redirect()->route('styles.show', $newStyle->id)->with('success', "Style duplicated as '{$newStyleNo}' successfully!");
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Failed to duplicate style: ' . $e->getMessage());
            return back()->with('error', 'Failed to duplicate style: ' . $e->getMessage());
        }
    }

    /**
     * Archive Style.
     */
    public function archive(Style $style): JsonResponse
    {
        $style->update(['status' => 'ARCHIVED']);
        return response()->json(['success' => true, 'message' => "Style '{$style->style_no}' archived."]);
    }

    /**
     * Update Style Construction (from Overview tab dropdown).
     */
    public function updateConstruction(Request $request, Style $style): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'construction' => 'nullable|string|max:100',
        ]);

        $style->update([
            'construction' => $validated['construction'] ?: null,
            'updated_by'   => auth()->id(),
        ]);

        if (function_exists('activity')) {
            activity()
                ->causedBy(auth()->user())
                ->performedOn($style)
                ->withProperties(['construction' => $style->construction])
                ->log("Updated construction for style {$style->style_no}");
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success'      => true,
                'message'      => 'Style construction updated successfully.',
                'construction' => $style->construction ?: 'Not Specified',
            ]);
        }

        return back()->with('success', 'Style construction updated successfully.');
    }

    /**
     * Update Style Treatment (from Overview tab dropdown).
     */
    public function updateTreatment(Request $request, Style $style): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'treatment' => 'nullable|string|max:100',
        ]);

        $style->update([
            'treatment'  => $validated['treatment'] ?: null,
            'updated_by' => auth()->id(),
        ]);

        if (function_exists('activity')) {
            activity()
                ->causedBy(auth()->user())
                ->performedOn($style)
                ->withProperties(['treatment' => $style->treatment])
                ->log("Updated treatment for style {$style->style_no}");
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success'   => true,
                'message'   => 'Style treatment updated successfully.',
                'treatment' => $style->treatment ?: 'Not Specified',
            ]);
        }

        return back()->with('success', 'Style treatment updated successfully.');
    }

    /**
     * Update Style Season (from Overview tab dropdown).
     */
    public function updateSeason(Request $request, Style $style): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'season' => 'nullable|string|max:100',
        ]);

        $style->update([
            'season'     => $validated['season'] ?: null,
            'updated_by' => auth()->id(),
        ]);

        if (function_exists('activity')) {
            activity()
                ->causedBy(auth()->user())
                ->performedOn($style)
                ->withProperties(['season' => $style->season])
                ->log("Updated season for style {$style->style_no}");
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Style season updated successfully.',
                'season'  => $style->season ?: 'Not Specified',
            ]);
        }

        return back()->with('success', 'Style season updated successfully.');
    }

    /**
     * ERP Bridge: Create Order from Style Master.
     */
    public function createOrder(Style $style): RedirectResponse
    {
        return redirect()->route('customer-pos.create', [
            'client_id'   => $style->client_id,
            'style_no'    => $style->style_no,
            'style_id'    => $style->id,
            'description' => $style->style_name,
        ]);
    }

    /**
     * Add BOM Item via AJAX.
     */
    public function storeBomItem(Request $request, Style $style): JsonResponse
    {
        $validated = $request->validate([
            'component_type'   => 'required|string|max:100',
            'item_description' => 'required|string|max:255',
            'supplier'         => 'nullable|string|max:150',
            'placement'        => 'nullable|string|max:150',
            'color'            => 'nullable|string|max:100',
            'qty_per_size'     => 'required|numeric|min:0',
            'uom'              => 'required|string|max:50',
            'unit_cost'        => 'required|numeric|min:0',
        ]);

        $validated['style_id'] = $style->id;
        $validated['total_cost'] = $validated['qty_per_size'] * $validated['unit_cost'];
        $validated['sort_order'] = $style->bomItems()->count() + 1;

        $item = StyleBomItem::create($validated);
        return response()->json(['success' => true, 'message' => 'BOM item added successfully!', 'item' => $item]);
    }

    /**
     * Add Measurement Point via AJAX.
     */
    public function storeMeasurement(Request $request, Style $style): JsonResponse
    {
        $validated = $request->validate([
            'measurement_point' => 'required|string|max:255',
            'spec_value'        => 'required|numeric',
            'tol_minus'         => 'nullable|numeric',
            'tol_plus'          => 'nullable|numeric',
            'xs'                => 'nullable|numeric',
            's'                 => 'nullable|numeric',
            'm'                 => 'nullable|numeric',
            'l'                 => 'nullable|numeric',
            'xl'                => 'nullable|numeric',
            'xxl'               => 'nullable|numeric',
            '3xl'               => 'nullable|numeric',
            '4xl'               => 'nullable|numeric',
            '5xl'               => 'nullable|numeric',
            '6xl'               => 'nullable|numeric',
            'uom'               => 'nullable|string|max:20',
        ]);

        $validated['style_id'] = $style->id;
        $validated['sort_order'] = $style->measurements()->count() + 1;

        $measurement = StyleMeasurement::create($validated);
        return response()->json(['success' => true, 'message' => 'Measurement point added successfully!', 'measurement' => $measurement]);
    }

    /**
     * Lookup Pantone details via AJAX.
     */
    public function lookupPantone(Request $request): JsonResponse
    {
        $code = $request->query('code', '');
        $info = PantoneService::lookup($code);

        if (!$info) {
            return response()->json(['success' => false, 'message' => 'Please enter a valid Pantone code.'], 400);
        }

        return response()->json(['success' => true, 'data' => $info]);
    }

    /**
     * Add Colorway via AJAX.
     */
    public function storeColorway(Request $request, Style $style): JsonResponse
    {
        $validated = $request->validate([
            'pantone_code'     => 'nullable|string|max:100',
            'color_name'       => 'nullable|string|max:100',
            'lab_op_ref'       => 'nullable|string|max:100',
            'swatch_color_hex' => 'nullable|string|max:20',
            'status'           => 'nullable|string|max:50',
        ]);

        if (empty($validated['color_name']) && empty($validated['pantone_code'])) {
            return response()->json(['success' => false, 'message' => 'Either a Color Name or a Pantone Color Code must be provided.'], 422);
        }

        // Auto-resolve Pantone details if code is provided
        if (!empty($validated['pantone_code'])) {
            $pantoneData = PantoneService::lookup($validated['pantone_code']);
            if ($pantoneData) {
                if (empty($validated['color_name'])) {
                    $validated['color_name'] = $pantoneData['name'];
                }
                if (empty($validated['swatch_color_hex']) || $validated['swatch_color_hex'] === '#1e293b') {
                    $validated['swatch_color_hex'] = $pantoneData['hex'];
                }
            }
        }

        if (empty($validated['color_name'])) {
            $validated['color_name'] = 'Custom Color';
        }
        if (empty($validated['swatch_color_hex'])) {
            $validated['swatch_color_hex'] = '#1e293b';
        }
        if (empty($validated['status'])) {
            $validated['status'] = 'Approved';
        }

        $validated['style_id'] = $style->id;
        $colorway = StyleColorway::create($validated);

        return response()->json(['success' => true, 'message' => 'Colorway swatch added!', 'colorway' => $colorway]);
    }

    /**
     * Add Sample via AJAX.
     */
    public function storeSample(Request $request, Style $style): JsonResponse
    {
        $validated = $request->validate([
            'sample_type_id' => 'nullable|exists:sample_types,id',
            'season'         => 'nullable|string|max:100',
            'sample_name'    => 'required|string|max:100',
            'sample_code'    => 'nullable|string|max:100',
            'tag_no'         => 'nullable|string|max:100',
            'sent_date'      => 'nullable|date',
            'waybill_no'     => 'nullable|string|max:100',
            'status'         => 'required|string|max:50',
            'submitted_by'   => 'nullable|string|max:100',
            'approval_date'  => 'nullable|date',
            'remarks'        => 'nullable|string',
            'qc_report'      => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:20480',
            'cost_sheet'     => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,csv,jpg,jpeg,png|max:20480',
            'comment_sheet'  => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,csv,jpg,jpeg,png|max:20480',
        ]);

        if ($request->hasFile('qc_report')) {
            $destinationPath = public_path('uploads/styles/samples/qc');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            $file = $request->file('qc_report');
            $filename = 'qc_' . $style->id . '_' . time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $file->getClientOriginalName());
            $file->move($destinationPath, $filename);
            $validated['qc_report'] = $filename;
        }

        if ($request->hasFile('cost_sheet')) {
            $destinationPath = public_path('uploads/styles/samples/cost');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            $file = $request->file('cost_sheet');
            $filename = 'cost_' . $style->id . '_' . time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $file->getClientOriginalName());
            $file->move($destinationPath, $filename);
            $validated['cost_sheet'] = $filename;
        }

        if ($request->hasFile('comment_sheet')) {
            $destinationPath = public_path('uploads/styles/samples/comments');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            $file = $request->file('comment_sheet');
            $filename = 'comment_' . $style->id . '_' . time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $file->getClientOriginalName());
            $file->move($destinationPath, $filename);
            $validated['comment_sheet'] = $filename;
        }

        $validated['style_id'] = $style->id;
        $validated['sort_order'] = $style->samples()->count() + 1;

        if ($validated['status'] === 'Approved' && empty($validated['approval_date'])) {
            $validated['approval_date'] = now()->toDateString();
        }

        $sample = StyleSample::create($validated);
        $sample->load('sampleType');

        return response()->json(['success' => true, 'message' => 'Sample record added successfully!', 'sample' => $sample]);
    }

    /**
     * Add Comment via AJAX.
     */
    public function addComment(Request $request, Style $style): JsonResponse
    {
        $request->validate(['comment' => 'required|string']);

        $comment = StyleComment::create([
            'style_id' => $style->id,
            'user_id'  => auth()->id(),
            'comment'  => $request->comment,
            'is_new'   => true,
        ]);

        $comment->load('user');

        return response()->json([
            'success'   => true,
            'message'   => 'Comment added!',
            'comment'   => $comment,
            'user_name' => auth()->user()->name,
            'time'      => $comment->created_at->format('d M Y, h:i A'),
        ]);
    }

    /**
     * Upload File via AJAX.
     */
    public function uploadFile(Request $request, Style $style): JsonResponse
    {
        $validated = $request->validate([
            'file'         => 'required|file|max:51200',
            'file_type_id' => 'nullable|exists:file_types,id',
            'folder'       => 'nullable|string|max:100',
            'description'  => 'nullable|string',
        ]);

        $file = $request->file('file');
        $originalName = $file->getClientOriginalName();
        $extension = strtoupper($file->getClientOriginalExtension() ?: 'FILE');
        $bytes = $file->getSize();

        // Format size
        if ($bytes >= 1048576) {
            $formattedSize = number_format($bytes / 1048576, 1) . ' MB';
        } else {
            $formattedSize = number_format($bytes / 1024, 0) . ' KB';
        }

        $destinationPath = public_path('uploads/styles/files');
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        $filename = 'doc_' . $style->id . '_' . time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $originalName);
        $file->move($destinationPath, $filename);

        $folder = $validated['folder'] ?? null;
        if (empty($folder) && !empty($validated['file_type_id'])) {
            $ft = FileType::find($validated['file_type_id']);
            $folder = $ft ? $ft->name : 'General';
        }

        $styleFile = StyleFile::create([
            'style_id'     => $style->id,
            'file_type_id' => $validated['file_type_id'] ?? null,
            'file_name'    => $originalName,
            'file_type'    => $extension,
            'file_size'    => $formattedSize,
            'folder'       => $folder ?: 'General',
            'description'  => $validated['description'] ?? null,
            'file_path'    => $filename,
            'uploaded_by'  => auth()->user()?->name ?: 'System',
        ]);

        $styleFile->load('fileType');

        if (function_exists('activity')) {
            activity()
                ->causedBy(auth()->user())
                ->performedOn($style)
                ->withProperties(['file_name' => $originalName, 'file_type' => $styleFile->fileType?->name])
                ->log("Uploaded document '{$originalName}' for style {$style->style_no}");
        }

        return response()->json(['success' => true, 'message' => 'Document uploaded successfully!', 'file' => $styleFile]);
    }

    /**
     * Delete File.
     */
    public function deleteFile(StyleFile $file): JsonResponse
    {
        $filePath = public_path('uploads/styles/files/' . $file->file_path);
        if (file_exists($filePath)) {
            @unlink($filePath);
        }
        $file->delete();
        return response()->json(['success' => true, 'message' => 'File deleted successfully.']);
    }

    /**
     * Delete Style Image.
     */
    public function deleteImage(StyleImage $image): JsonResponse
    {
        $filePath = public_path('uploads/styles/' . $image->image_path);
        if (file_exists($filePath)) {
            @unlink($filePath);
        }
        $image->delete();
        return response()->json(['success' => true, 'message' => 'Style image deleted successfully.']);
    }

    /**
     * Update Tech Pack specs 
     */
    public function updateTechPack(Request $request, Style $style): JsonResponse
    {
        $validated = $request->validate([
            'fitting'           => 'nullable|string|max:100',
            'stitching'         => 'nullable|string|max:150',
            'label_branding'    => 'nullable|string|max:150',
            'care_instructions' => 'nullable|string|max:255',
            'packaging_notes'   => 'nullable|string|max:255',
            'drawcord_notes'    => 'nullable|string|max:150',
            'seam_notes'        => 'nullable|string|max:150',
            'thread_notes'      => 'nullable|string|max:150',
            'remarks'           => 'nullable|string',
            'tech_pack_pdf'     => 'nullable|file|mimes:pdf|max:20480',
        ]);

        $techPack = $style->techPack ?: new StyleTechPack(['style_id' => $style->id]);

        // Handle PDF file upload
        if ($request->hasFile('tech_pack_pdf')) {
            $destinationPath = public_path('uploads/styles/techpacks');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            // Delete old PDF if it exists
            if ($techPack->pdf_path) {
                $oldPath = public_path('uploads/styles/techpacks/' . $techPack->pdf_path);
                if (file_exists($oldPath)) {
                    @unlink($oldPath);
                }
            }

            $pdf = $request->file('tech_pack_pdf');
            $filename = 'techpack_' . $style->id . '_' . time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $pdf->getClientOriginalName());
            $pdf->move($destinationPath, $filename);
            $validated['pdf_path'] = $filename;
        }

        // Remove the file field from validated data before fill
        unset($validated['tech_pack_pdf']);

        $techPack->fill($validated);
        $techPack->style_id = $style->id;
        $techPack->save();

        return response()->json(['success' => true, 'message' => 'Tech Pack updated successfully!', 'tech_pack' => $techPack]);
    }

    /**
     * Delete Tech Pack PDF.
     */
    public function deleteTechPackPdf(Style $style): JsonResponse
    {
        $techPack = $style->techPack;

        if (!$techPack || !$techPack->pdf_path) {
            return response()->json(['success' => false, 'message' => 'No PDF found for this tech pack.'], 404);
        }

        $filePath = public_path('uploads/styles/techpacks/' . $techPack->pdf_path);
        if (file_exists($filePath)) {
            @unlink($filePath);
        }

        $techPack->update(['pdf_path' => null]);

        return response()->json(['success' => true, 'message' => 'Tech Pack PDF deleted successfully.']);
    }

    /**
     * Upload Tech Pack Document classified by Tech Pack Type.
     */
    public function uploadTechPackDocument(Request $request, Style $style): JsonResponse
    {
        $validated = $request->validate([
            'tech_pack_type_id' => 'required|exists:tech_pack_types,id',
            'file'              => 'required|file|max:30720|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,png,jpg,jpeg,svg,zip',
            'document_name'     => 'nullable|string|max:255',
            'notes'             => 'nullable|string',
        ]);

        $file = $request->file('file');
        $originalName = $file->getClientOriginalName();
        $extension = strtoupper($file->getClientOriginalExtension() ?: 'FILE');
        $bytes = $file->getSize();

        if ($bytes >= 1048576) {
            $formattedSize = number_format($bytes / 1048576, 1) . ' MB';
        } else {
            $formattedSize = number_format($bytes / 1024, 0) . ' KB';
        }

        $destinationPath = public_path('uploads/styles/techpacks/documents');
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        $type = TechPackType::find($validated['tech_pack_type_id']);
        $filename = 'tp_' . $style->id . '_' . $type->id . '_' . time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $originalName);
        $file->move($destinationPath, $filename);

        $doc = StyleTechPackDocument::create([
            'style_id'          => $style->id,
            'tech_pack_type_id' => $type->id,
            'type_name'         => $type->name,
            'document_name'     => $validated['document_name'] ?: $originalName,
            'file_path'         => $filename,
            'file_type'         => $extension,
            'file_size'         => $formattedSize,
            'notes'             => $validated['notes'] ?? null,
            'uploaded_by'       => auth()->id(),
        ]);

        $doc->load(['techPackType', 'uploadedByUser']);

        if (function_exists('activity')) {
            activity()
                ->causedBy(auth()->user())
                ->performedOn($style)
                ->withProperties(['type' => $type->name, 'document_name' => $doc->document_name])
                ->log("Uploaded Tech Pack document '{$doc->document_name}' ({$type->name}) for style {$style->style_no}");
        }

        return response()->json([
            'success'  => true,
            'message'  => "Tech Pack document uploaded successfully under '{$type->name}'!",
            'document' => $doc,
        ]);
    }

    /**
     * Delete Tech Pack Document.
     */
    public function deleteTechPackDocument(StyleTechPackDocument $document): JsonResponse
    {
        $filePath = public_path('uploads/styles/techpacks/documents/' . $document->file_path);
        if (file_exists($filePath)) {
            @unlink($filePath);
        }

        $docName = $document->document_name;
        $document->delete();

        return response()->json([
            'success' => true,
            'message' => "Tech Pack document '{$docName}' deleted successfully.",
        ]);
    }

    /**
     * Update BOM Item.
     */
    public function updateBomItem(Request $request, StyleBomItem $item): JsonResponse
    {
        $validated = $request->validate([
            'component_type'   => 'required|string|max:100',
            'item_description' => 'required|string|max:255',
            'supplier'         => 'nullable|string|max:150',
            'placement'        => 'nullable|string|max:150',
            'color'            => 'nullable|string|max:100',
            'qty_per_size'     => 'required|numeric|min:0',
            'uom'              => 'required|string|max:50',
            'unit_cost'        => 'required|numeric|min:0',
        ]);

        $validated['total_cost'] = $validated['qty_per_size'] * $validated['unit_cost'];
        $item->update($validated);

        return response()->json(['success' => true, 'message' => 'BOM item updated successfully!', 'item' => $item]);
    }

    /**
     * Delete BOM Item.
     */
    public function deleteBomItem(StyleBomItem $item): JsonResponse
    {
        $item->delete();
        return response()->json(['success' => true, 'message' => 'BOM item deleted successfully.']);
    }

    /**
     * Update Measurement Point.
     */
    public function updateMeasurement(Request $request, StyleMeasurement $measurement): JsonResponse
    {
        $validated = $request->validate([
            'measurement_point' => 'required|string|max:255',
            'spec_value'        => 'required|numeric',
            'tol_minus'         => 'nullable|numeric',
            'tol_plus'          => 'nullable|numeric',
            'xs'                => 'nullable|numeric',
            's'                 => 'nullable|numeric',
            'm'                 => 'nullable|numeric',
            'l'                 => 'nullable|numeric',
            'xl'                => 'nullable|numeric',
            'xxl'               => 'nullable|numeric',
            '3xl'               => 'nullable|numeric',
            '4xl'               => 'nullable|numeric',
            '5xl'               => 'nullable|numeric',
            '6xl'               => 'nullable|numeric',
            'uom'               => 'nullable|string|max:20',
        ]);

        $measurement->update($validated);
        return response()->json(['success' => true, 'message' => 'Measurement point updated successfully!', 'measurement' => $measurement]);
    }

    /**
     * Delete Measurement Point.
     */
    public function deleteMeasurement(StyleMeasurement $measurement): JsonResponse
    {
        $measurement->delete();
        return response()->json(['success' => true, 'message' => 'Measurement point deleted successfully.']);
    }

    /**
     * Update Colorway.
     */
    public function updateColorway(Request $request, StyleColorway $colorway): JsonResponse
    {
        $validated = $request->validate([
            'pantone_code'     => 'nullable|string|max:100',
            'color_name'       => 'nullable|string|max:100',
            'lab_op_ref'       => 'nullable|string|max:100',
            'swatch_color_hex' => 'nullable|string|max:20',
            'status'           => 'nullable|string|max:50',
        ]);

        if (empty($validated['color_name']) && empty($validated['pantone_code'])) {
            return response()->json(['success' => false, 'message' => 'Either a Color Name or a Pantone Color Code must be provided.'], 422);
        }

        if (!empty($validated['pantone_code'])) {
            $pantoneData = PantoneService::lookup($validated['pantone_code']);
            if ($pantoneData) {
                if (empty($validated['color_name'])) {
                    $validated['color_name'] = $pantoneData['name'];
                }
                if (empty($validated['swatch_color_hex'])) {
                    $validated['swatch_color_hex'] = $pantoneData['hex'];
                }
            }
        }

        if (empty($validated['color_name'])) {
            $validated['color_name'] = $colorway->color_name ?: 'Custom Color';
        }

        $colorway->update($validated);
        return response()->json(['success' => true, 'message' => 'Colorway swatch updated successfully!', 'colorway' => $colorway]);
    }

    /**
     * Delete Colorway.
     */
    public function deleteColorway(StyleColorway $colorway): JsonResponse
    {
        $colorway->delete();
        return response()->json(['success' => true, 'message' => 'Colorway swatch deleted.']);
    }

    /**
     * Update Sample Record.
     */
    public function updateSample(Request $request, StyleSample $sample): JsonResponse
    {
        $validated = $request->validate([
            'sample_type_id' => 'nullable|exists:sample_types,id',
            'season'         => 'nullable|string|max:100',
            'sample_name'    => 'required|string|max:100',
            'sample_code'    => 'nullable|string|max:100',
            'tag_no'         => 'nullable|string|max:100',
            'sent_date'      => 'nullable|date',
            'waybill_no'     => 'nullable|string|max:100',
            'status'         => 'required|string|max:50',
            'submitted_by'   => 'nullable|string|max:100',
            'approval_date'  => 'nullable|date',
            'remarks'        => 'nullable|string',
            'qc_report'      => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:20480',
            'cost_sheet'     => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,csv,jpg,jpeg,png|max:20480',
            'comment_sheet'  => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,csv,jpg,jpeg,png|max:20480',
        ]);

        if ($request->hasFile('qc_report')) {
            $destinationPath = public_path('uploads/styles/samples/qc');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            // Delete old QC report if exists
            if ($sample->qc_report) {
                $oldPath = public_path('uploads/styles/samples/qc/' . $sample->qc_report);
                if (file_exists($oldPath)) {
                    @unlink($oldPath);
                }
            }

            $file = $request->file('qc_report');
            $filename = 'qc_' . $sample->style_id . '_' . time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $file->getClientOriginalName());
            $file->move($destinationPath, $filename);
            $validated['qc_report'] = $filename;
        }

        if ($request->hasFile('cost_sheet')) {
            $destinationPath = public_path('uploads/styles/samples/cost');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            // Delete old cost sheet if exists
            if ($sample->cost_sheet) {
                $oldPath = public_path('uploads/styles/samples/cost/' . $sample->cost_sheet);
                if (file_exists($oldPath)) {
                    @unlink($oldPath);
                }
            }

            $file = $request->file('cost_sheet');
            $filename = 'cost_' . $sample->style_id . '_' . time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $file->getClientOriginalName());
            $file->move($destinationPath, $filename);
            $validated['cost_sheet'] = $filename;
        }

        if ($request->hasFile('comment_sheet')) {
            $destinationPath = public_path('uploads/styles/samples/comments');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            // Delete old comment sheet if exists
            if ($sample->comment_sheet) {
                $oldPath = public_path('uploads/styles/samples/comments/' . $sample->comment_sheet);
                if (file_exists($oldPath)) {
                    @unlink($oldPath);
                }
            }

            $file = $request->file('comment_sheet');
            $filename = 'comment_' . $sample->style_id . '_' . time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $file->getClientOriginalName());
            $file->move($destinationPath, $filename);
            $validated['comment_sheet'] = $filename;
        }

        if ($validated['status'] === 'Approved' && empty($validated['approval_date'])) {
            $validated['approval_date'] = $sample->approval_date ?: now()->toDateString();
        }

        $sample->update($validated);
        $sample->load('sampleType');
        return response()->json(['success' => true, 'message' => 'Sample record updated successfully!', 'sample' => $sample]);
    }

    /**
     * Change Sample Type directly for a sample.
     */
    public function changeSampleType(Request $request, StyleSample $sample): JsonResponse
    {
        $validated = $request->validate([
            'sample_type_id' => 'nullable|exists:sample_types,id',
        ]);

        $sample->update($validated);
        $sample->load('sampleType');

        $typeName = $sample->sampleType ? $sample->sampleType->name : 'None';
        return response()->json([
            'success'     => true,
            'message'     => "Sample type changed to '{$typeName}' successfully!",
            'sample'      => $sample,
            'sample_type' => $sample->sampleType,
        ]);
    }

    /**
     * Change Sample Status directly for a sample (auto-sets approval date on Approved).
     */
    public function changeSampleStatus(Request $request, StyleSample $sample): JsonResponse
    {
        $validated = $request->validate([
            'status' => 'required|string|in:Pending,Approved,Revised,Rejected',
        ]);

        $updateData = ['status' => $validated['status']];

        if ($validated['status'] === 'Approved') {
            // Auto-set approval date to today when approved
            $updateData['approval_date'] = $sample->approval_date ?: now()->toDateString();
        } else {
            // Clear approval date if not approved
            $updateData['approval_date'] = null;
        }

        $sample->update($updateData);

        return response()->json([
            'success'                 => true,
            'message'                 => "Sample status updated to '{$sample->status}' successfully!",
            'sample'                  => $sample,
            'approval_date_formatted' => $sample->approval_date ? $sample->approval_date->format('d M Y') : '—',
        ]);
    }

    /**
     * Change Sample Season directly for a sample.
     */
    public function changeSampleSeason(Request $request, StyleSample $sample): JsonResponse
    {
        $validated = $request->validate([
            'season' => 'nullable|string|max:100',
        ]);

        $sample->update(['season' => $validated['season'] ?: null]);

        return response()->json([
            'success' => true,
            'message' => "Sample season updated to '" . ($sample->season ?: 'None') . "' successfully!",
            'sample'  => $sample,
        ]);
    }

    /**
     * Quick Change Pattern File Type directly for a sample.
     */
    public function changeSamplePatternType(Request $request, StyleSample $sample): JsonResponse
    {
        $validated = $request->validate([
            'pattern_file_type_id' => 'nullable|exists:pattern_file_types,id',
        ]);

        $sample->update(['pattern_file_type_id' => $validated['pattern_file_type_id'] ?: null]);
        $sample->load('patternFileType');

        return response()->json([
            'success'           => true,
            'message'           => 'Pattern file type updated successfully!',
            'sample'            => $sample,
            'pattern_file_type' => $sample->patternFileType,
        ]);
    }

    /**
     * Upload Pattern File for a sample.
     */
    public function uploadSamplePattern(Request $request, StyleSample $sample): JsonResponse
    {
        $validated = $request->validate([
            'pattern_file'         => 'required|file|max:10240',
            'pattern_file_type_id' => 'nullable|exists:pattern_file_types,id',
            'pattern_notes'        => 'nullable|string',
        ]);

        $destinationPath = public_path('uploads/styles/patterns');
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        if ($sample->pattern_file) {
            $oldPath = public_path('uploads/styles/patterns/' . $sample->pattern_file);
            if (file_exists($oldPath)) {
                @unlink($oldPath);
            }
        }

        $file = $request->file('pattern_file');
        $filename = 'pat_' . $sample->style_id . '_' . $sample->id . '_' . time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $file->getClientOriginalName());
        $file->move($destinationPath, $filename);

        $sample->update([
            'pattern_file'         => $filename,
            'pattern_uploaded_at'  => now(),
            'pattern_file_type_id' => $validated['pattern_file_type_id'] ?? $sample->pattern_file_type_id,
            'pattern_notes'        => $validated['pattern_notes'] ?? $sample->pattern_notes,
        ]);
        $sample->load('patternFileType');

        return response()->json([
            'success'   => true,
            'message'   => 'Pattern file uploaded successfully!',
            'sample'    => $sample,
            'file_name' => $filename,
        ]);
    }

    /**
     * Download Sample Pattern File.
     */
    public function downloadSamplePattern(StyleSample $sample)
    {
        if (!$sample->pattern_file) {
            return back()->with('error', 'No pattern file uploaded for this sample.');
        }

        $filePath = public_path('uploads/styles/patterns/' . $sample->pattern_file);
        if (!file_exists($filePath)) {
            abort(404, 'Pattern file not found on server.');
        }

        return response()->download($filePath);
    }

    /**
     * Delete Sample Pattern File.
     */
    public function deleteSamplePattern(StyleSample $sample): JsonResponse
    {
        if ($sample->pattern_file) {
            $path = public_path('uploads/styles/patterns/' . $sample->pattern_file);
            if (file_exists($path)) {
                @unlink($path);
            }
        }

        $sample->update([
            'pattern_file'        => null,
            'pattern_uploaded_at' => null,
            'pattern_notes'       => null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pattern file deleted successfully.',
        ]);
    }

    /**
     * Delete Sample Record.
     */
    public function deleteSample(StyleSample $sample): JsonResponse
    {
        if ($sample->qc_report) {
            $oldPath = public_path('uploads/styles/samples/qc/' . $sample->qc_report);
            if (file_exists($oldPath)) {
                @unlink($oldPath);
            }
        }

        if ($sample->pattern_file) {
            $patternPath = public_path('uploads/styles/patterns/' . $sample->pattern_file);
            if (file_exists($patternPath)) {
                @unlink($patternPath);
            }
        }

        $sample->delete();
        return response()->json(['success' => true, 'message' => 'Sample record deleted.']);
    }

    /**
     * Store Artwork.
     */
    public function storeArtwork(Request $request, Style $style): JsonResponse
    {
        $validated = $request->validate([
            'artwork_name'    => 'required|string|max:150',
            'artwork_code'    => 'nullable|string|max:100',
            'type'            => 'required|string|max:100',
            'artwork_type_id' => 'nullable|exists:artwork_types,id',
            'placement'       => 'nullable|string|max:150',
            'dimensions'      => 'nullable|string|max:100',
            'colors'          => 'nullable|array',
            'artwork_file'    => 'nullable|file|max:102400',
        ]);

        if (empty($validated['artwork_type_id']) && !empty($validated['type'])) {
            $matchedType = ArtworkType::where('name', $validated['type'])->first();
            if ($matchedType) {
                $validated['artwork_type_id'] = $matchedType->id;
            }
        }

        if ($request->hasFile('artwork_file')) {
            $destinationPath = public_path('uploads/styles/artworks');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            $file = $request->file('artwork_file');
            $filename = 'art_' . $style->id . '_' . time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $file->getClientOriginalName());
            $file->move($destinationPath, $filename);
            $validated['artwork_file'] = $filename;
        }

        $validated['style_id'] = $style->id;
        $artwork = StyleArtwork::create($validated);
        $artwork->load('artworkType');

        return response()->json(['success' => true, 'message' => 'Artwork record added successfully!', 'artwork' => $artwork]);
    }

    /**
     * Update Artwork.
     */
    public function updateArtwork(Request $request, StyleArtwork $artwork): JsonResponse
    {
        $validated = $request->validate([
            'artwork_name'    => 'required|string|max:150',
            'artwork_code'    => 'nullable|string|max:100',
            'type'            => 'required|string|max:100',
            'artwork_type_id' => 'nullable|exists:artwork_types,id',
            'placement'       => 'nullable|string|max:150',
            'dimensions'      => 'nullable|string|max:100',
            'colors'          => 'nullable|array',
            'artwork_file'    => 'nullable|file|max:102400',
        ]);

        if (empty($validated['artwork_type_id']) && !empty($validated['type'])) {
            $matchedType = ArtworkType::where('name', $validated['type'])->first();
            if ($matchedType) {
                $validated['artwork_type_id'] = $matchedType->id;
            }
        }

        if ($request->hasFile('artwork_file')) {
            $destinationPath = public_path('uploads/styles/artworks');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            if ($artwork->artwork_file) {
                $oldPath = public_path('uploads/styles/artworks/' . $artwork->artwork_file);
                if (file_exists($oldPath)) {
                    @unlink($oldPath);
                }
            }

            $file = $request->file('artwork_file');
            $filename = 'art_' . $artwork->style_id . '_' . time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $file->getClientOriginalName());
            $file->move($destinationPath, $filename);
            $validated['artwork_file'] = $filename;
        }

        $artwork->update($validated);
        $artwork->load('artworkType');
        return response()->json(['success' => true, 'message' => 'Artwork record updated successfully!', 'artwork' => $artwork]);
    }

    /**
     * Delete Artwork.
     */
    public function deleteArtwork(StyleArtwork $artwork): JsonResponse
    {
        if ($artwork->artwork_file) {
            $path = public_path('uploads/styles/artworks/' . $artwork->artwork_file);
            if (file_exists($path)) {
                @unlink($path);
            }
        }

        $artwork->delete();
        return response()->json(['success' => true, 'message' => 'Artwork deleted.']);
    }

    /**
     * Download Artwork File.
     */
    public function downloadArtworkFile(StyleArtwork $artwork)
    {
        if (!$artwork->artwork_file) {
            return back()->with('error', 'No artwork file attached to this record.');
        }

        $filePath = public_path('uploads/styles/artworks/' . $artwork->artwork_file);
        if (!file_exists($filePath)) {
            abort(404, 'Artwork file not found on server.');
        }

        return response()->download($filePath);
    }

    /**
     * Update Costing Settings.
     */
    public function updateCosting(Request $request, Style $style): JsonResponse
    {
        $validated = $request->validate([
            'currency'         => 'required|string|max:10',
            'overhead_percent' => 'required|numeric|min:0',
            'profit_percent'   => 'required|numeric|min:0',
            'target_price'     => 'nullable|numeric|min:0',
        ]);

        $costing = $style->costing ?: new StyleCosting(['style_id' => $style->id]);
        $costing->fill($validated);
        $costing->style_id = $style->id;

        // Recalculate material direct cost from BOM
        $materialCost = $style->bomItems->sum(fn($i) => $i->total_cost ?: ($i->qty_per_size * $i->unit_cost));
        $mfgCost = 1.95; // Default standard mfg cost
        $totalDirect = $materialCost + $mfgCost;

        $ohAmount = round(($totalDirect * $validated['overhead_percent']) / 100, 2);
        $totalCostWithOh = $totalDirect + $ohAmount;
        $profitAmount = round(($totalCostWithOh * $validated['profit_percent']) / 100, 2);
        $calculatedTarget = $totalCostWithOh + $profitAmount;

        $costing->total_direct_cost = $totalDirect;
        $costing->overhead_amount = $ohAmount;
        $costing->profit_amount = $profitAmount;
        $costing->total_cost = $totalCostWithOh;
        if (empty($validated['target_price'])) {
            $costing->target_price = $calculatedTarget;
        }

        $costing->save();

        return response()->json(['success' => true, 'message' => 'Costing sheet updated successfully!', 'costing' => $costing]);
    }

    /**
     * Store Approval Gate.
     */
    public function storeApproval(Request $request, Style $style): JsonResponse
    {
        $validated = $request->validate([
            'gate_name'     => 'required|string|max:150',
            'status'        => 'required|string|max:50',
            'approved_by'   => 'nullable|string|max:100',
            'approval_date' => 'nullable|date',
            'remarks'       => 'nullable|string',
        ]);

        $validated['style_id'] = $style->id;
        $validated['sort_order'] = $style->approvals()->count() + 1;
        $approval = StyleApproval::create($validated);

        return response()->json(['success' => true, 'message' => 'Approval gate added successfully!', 'approval' => $approval]);
    }

    /**
     * Update Approval Gate.
     */
    public function updateApproval(Request $request, StyleApproval $approval): JsonResponse
    {
        $validated = $request->validate([
            'gate_name'     => 'required|string|max:150',
            'status'        => 'required|string|max:50',
            'approved_by'   => 'nullable|string|max:100',
            'approval_date' => 'nullable|date',
            'remarks'       => 'nullable|string',
        ]);

        $approval->update($validated);
        return response()->json(['success' => true, 'message' => 'Approval sign-off updated successfully!', 'approval' => $approval]);
    }

    /**
     * Delete Approval Gate.
     */
    public function deleteApproval(StyleApproval $approval): JsonResponse
    {
        $approval->delete();
        return response()->json(['success' => true, 'message' => 'Approval gate deleted.']);
    }

    /**
     * Quick Upload Style Image (via AJAX from Overview or Edit screen).
     */
    public function uploadImage(Request $request, Style $style): JsonResponse
    {
        $request->validate([
            'image'      => 'required|image|mimes:jpeg,png,jpg,webp,svg|max:10240',
            'image_type' => 'required|in:Front,Back,Details,Colorways',
        ]);

        $destinationPath = public_path('uploads/styles');
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        $file = $request->file('image');
        $type = $request->image_type;
        $filename = 'style_' . $style->id . '_' . strtolower($type) . '_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $file->move($destinationPath, $filename);

        $styleImage = StyleImage::create([
            'style_id'   => $style->id,
            'image_type' => $type,
            'image_path' => $filename,
            'is_primary' => ($type === 'Front' && !$style->images()->where('is_primary', true)->exists()),
            'sort_order' => $style->images()->count() + 1,
        ]);

        if (function_exists('activity')) {
            activity()
                ->causedBy(auth()->user())
                ->performedOn($style)
                ->withProperties(['image_type' => $type, 'image_path' => $filename])
                ->log("Uploaded {$type} image for style {$style->style_no}");
        }

        return response()->json([
            'success' => true,
            'message' => "{$type} image uploaded successfully!",
            'image'   => $styleImage,
            'url'     => asset('uploads/styles/' . $filename),
        ]);
    }
}

