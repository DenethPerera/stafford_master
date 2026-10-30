<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\Masters\ArtworkTypeController;
use App\Http\Controllers\Masters\PatternFileTypeController;
use App\Http\Controllers\Masters\FileTypeController;
use App\Http\Controllers\Masters\ColorController;
use App\Http\Controllers\Masters\ConstructionController;
use App\Http\Controllers\Masters\DesignPositionController;
use App\Http\Controllers\Masters\SampleTypeController;
use App\Http\Controllers\Masters\ScaleController;
use App\Http\Controllers\Masters\SeasonController as PlmSeasonController;
use App\Http\Controllers\Masters\SizeController;
use App\Http\Controllers\Masters\TechPackTypeController;
use App\Http\Controllers\StyleController;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    // ─── PLM Styles Workspace (from temp_friend_module) ──────────────────────
    Route::prefix('styles')->name('styles.')->group(function () {
        Route::get('/', [StyleController::class, 'index'])->name('index');
        Route::get('/create', [StyleController::class, 'create'])->name('create');
        Route::post('/', [StyleController::class, 'store'])->name('store');
        Route::get('/{style}', [StyleController::class, 'show'])->name('show');
        Route::get('/{style}/edit', [StyleController::class, 'edit'])->name('edit');
        Route::put('/{style}', [StyleController::class, 'update'])->name('update');
        Route::post('/{style}/duplicate', [StyleController::class, 'duplicate'])->name('duplicate');
        Route::post('/{style}/archive', [StyleController::class, 'archive'])->name('archive');
        Route::patch('/{style}/construction', [StyleController::class, 'updateConstruction'])->name('update-construction');
        Route::patch('/{style}/treatment', [StyleController::class, 'updateTreatment'])->name('update-treatment');
        Route::patch('/{style}/season', [StyleController::class, 'updateSeason'])->name('update-season');
        Route::patch('/{style}/plm-no', [StyleController::class, 'updatePlmNo'])->name('update-plm-no');
        Route::patch('/{style}/special-instructions', [StyleController::class, 'updateSpecialInstructions'])->name('update-special-instructions');

        // Sub-resource AJAX endpoints for PLM Workspace Tabs
        Route::put('/{style}/tech-pack', [StyleController::class, 'updateTechPack'])->name('tech-pack.update');
        Route::delete('/{style}/tech-pack-pdf', [StyleController::class, 'deleteTechPackPdf'])->name('tech-pack-pdf.destroy');
        Route::post('/{style}/tech-pack-documents', [StyleController::class, 'uploadTechPackDocument'])->name('tech-pack-documents.upload');
        Route::delete('/tech-pack-documents/{document}', [StyleController::class, 'deleteTechPackDocument'])->name('tech-pack-documents.destroy');

        Route::post('/{style}/bom', [StyleController::class, 'storeBomItem'])->name('bom.store');
        Route::put('/bom/{item}', [StyleController::class, 'updateBomItem'])->name('bom.update');
        Route::delete('/bom/{item}', [StyleController::class, 'deleteBomItem'])->name('bom.destroy');

        Route::post('/{style}/measurements', [StyleController::class, 'storeMeasurement'])->name('measurements.store');
        Route::put('/measurements/{measurement}', [StyleController::class, 'updateMeasurement'])->name('measurements.update');
        Route::delete('/measurements/{measurement}', [StyleController::class, 'deleteMeasurement'])->name('measurements.destroy');

        Route::get('/pantone/lookup', [StyleController::class, 'lookupPantone'])->name('pantone.lookup');
        Route::post('/{style}/colorways', [StyleController::class, 'storeColorway'])->name('colorways.store');
        Route::put('/colorways/{colorway}', [StyleController::class, 'updateColorway'])->name('colorways.update');
        Route::delete('/colorways/{colorway}', [StyleController::class, 'deleteColorway'])->name('colorways.destroy');

        Route::post('/{style}/samples', [StyleController::class, 'storeSample'])->name('samples.store');
        Route::put('/samples/{sample}', [StyleController::class, 'updateSample'])->name('samples.update');
        Route::patch('/samples/{sample}/change-type', [StyleController::class, 'changeSampleType'])->name('samples.change-type');
        Route::patch('/samples/{sample}/change-status', [StyleController::class, 'changeSampleStatus'])->name('samples.change-status');
        Route::patch('/samples/{sample}/change-season', [StyleController::class, 'changeSampleSeason'])->name('samples.change-season');
        Route::patch('/samples/{sample}/change-pattern-type', [StyleController::class, 'changeSamplePatternType'])->name('samples.change-pattern-type');
        Route::post('/samples/{sample}/pattern/upload', [StyleController::class, 'uploadSamplePattern'])->name('samples.pattern.upload');
        Route::get('/samples/{sample}/pattern/download', [StyleController::class, 'downloadSamplePattern'])->name('samples.pattern.download');
        Route::delete('/samples/{sample}/pattern', [StyleController::class, 'deleteSamplePattern'])->name('samples.pattern.destroy');
        Route::delete('/samples/{sample}', [StyleController::class, 'deleteSample'])->name('samples.destroy');

        Route::post('/{style}/artworks', [StyleController::class, 'storeArtwork'])->name('artworks.store');
        Route::put('/artworks/{artwork}', [StyleController::class, 'updateArtwork'])->name('artworks.update');
        Route::delete('/artworks/{artwork}', [StyleController::class, 'deleteArtwork'])->name('artworks.destroy');
        Route::get('/artworks/{artwork}/download', [StyleController::class, 'downloadArtworkFile'])->name('artworks.download');

        Route::put('/{style}/costing', [StyleController::class, 'updateCosting'])->name('costing.update');

        Route::post('/{style}/comments', [StyleController::class, 'addComment'])->name('comments.store');

        Route::post('/{style}/files', [StyleController::class, 'uploadFile'])->name('files.upload');
        Route::delete('/files/{file}', [StyleController::class, 'deleteFile'])->name('files.destroy');
        Route::post('/{style}/images', [StyleController::class, 'uploadImage'])->name('images.upload');
        Route::delete('/images/{image}', [StyleController::class, 'deleteImage'])->name('images.destroy');

        Route::post('/{style}/approvals', [StyleController::class, 'storeApproval'])->name('approvals.store');
        Route::put('/approvals/{approval}', [StyleController::class, 'updateApproval'])->name('approvals.update');
        Route::delete('/approvals/{approval}', [StyleController::class, 'deleteApproval'])->name('approvals.destroy');

        Route::post('/{style}/trim-submissions', [StyleController::class, 'storeTrimSubmission'])->name('trim-submissions.store');
        Route::put('/trim-submissions/{trimSubmission}', [StyleController::class, 'updateTrimSubmission'])->name('trim-submissions.update');
        Route::delete('/trim-submissions/{trimSubmission}', [StyleController::class, 'deleteTrimSubmission'])->name('trim-submissions.destroy');
        Route::patch('/trim-submissions/{trimSubmission}/change-status', [StyleController::class, 'changeTrimSubmissionStatus'])->name('trim-submissions.change-status');

        // Carton Dimensions
        Route::post('/{style}/dimensions', [StyleController::class, 'storeCartonDimension'])->name('dimensions.store');
        Route::put('/dimensions/{dimension}', [StyleController::class, 'updateCartonDimension'])->name('dimensions.update');
        Route::delete('/dimensions/{dimension}', [StyleController::class, 'deleteCartonDimension'])->name('dimensions.destroy');
        Route::post('/{style}/dimensions/upload', [StyleController::class, 'uploadCartonDimensionsCsv'])->name('dimensions.upload');
        Route::get('/{style}/dimensions/export', [StyleController::class, 'exportCartonDimensionsCsv'])->name('dimensions.export');
    });

    // Legacy redirect for /designs
    Route::get('designs', fn() => redirect()->route('styles.index'));

    // ─── PLM Master Files (from temp_friend_module) ───────────────────────────
    Route::prefix('masters')->name('masters.')->group(function () {

        // Colors
        Route::prefix('colors')->name('colors.')->group(function () {
            Route::get('/', [ColorController::class, 'index'])->name('index');
            Route::post('/', [ColorController::class, 'store'])->name('store');
            Route::get('/{color}', [ColorController::class, 'show'])->name('show');
            Route::put('/{color}', [ColorController::class, 'update'])->name('update');
            Route::delete('/{color}', [ColorController::class, 'destroy'])->name('destroy');
        });

        // Sizes
        Route::prefix('sizes')->name('sizes.')->group(function () {
            Route::get('/', [SizeController::class, 'index'])->name('index');
            Route::post('/', [SizeController::class, 'store'])->name('store');
            Route::get('/{size}', [SizeController::class, 'show'])->name('show');
            Route::put('/{size}', [SizeController::class, 'update'])->name('update');
            Route::delete('/{size}', [SizeController::class, 'destroy'])->name('destroy');
        });

        // Scales
        Route::prefix('scales')->name('scales.')->group(function () {
            Route::get('/', [ScaleController::class, 'index'])->name('index');
            Route::post('/', [ScaleController::class, 'store'])->name('store');
            Route::get('/{scale}', [ScaleController::class, 'show'])->name('show');
            Route::put('/{scale}', [ScaleController::class, 'update'])->name('update');
            Route::delete('/{scale}', [ScaleController::class, 'destroy'])->name('destroy');
        });

        // Design Positions
        Route::prefix('design-positions')->name('design-positions.')->group(function () {
            Route::get('/', [DesignPositionController::class, 'index'])->name('index');
            Route::post('/', [DesignPositionController::class, 'store'])->name('store');
            Route::get('/{designPosition}', [DesignPositionController::class, 'show'])->name('show');
            Route::put('/{designPosition}', [DesignPositionController::class, 'update'])->name('update');
            Route::delete('/{designPosition}', [DesignPositionController::class, 'destroy'])->name('destroy');
        });

        // Constructions
        Route::prefix('constructions')->name('constructions.')->group(function () {
            Route::get('/', [ConstructionController::class, 'index'])->name('index');
            Route::post('/', [ConstructionController::class, 'store'])->name('store');
            Route::get('/{construction}', [ConstructionController::class, 'show'])->name('show');
            Route::put('/{construction}', [ConstructionController::class, 'update'])->name('update');
            Route::delete('/{construction}', [ConstructionController::class, 'destroy'])->name('destroy');
        });

        // Sample Types
        Route::prefix('sample-types')->name('sample-types.')->group(function () {
            Route::get('/', [SampleTypeController::class, 'index'])->name('index');
            Route::post('/', [SampleTypeController::class, 'store'])->name('store');
            Route::get('/{sampleType}', [SampleTypeController::class, 'show'])->name('show');
            Route::put('/{sampleType}', [SampleTypeController::class, 'update'])->name('update');
            Route::delete('/{sampleType}', [SampleTypeController::class, 'destroy'])->name('destroy');
        });

        // Seasons (PLM - aliased to PlmSeasonController to avoid conflict)
        Route::prefix('seasons')->name('seasons.')->group(function () {
            Route::get('/', [PlmSeasonController::class, 'index'])->name('index');
            Route::post('/', [PlmSeasonController::class, 'store'])->name('store');
            Route::get('/{season}', [PlmSeasonController::class, 'show'])->name('show');
            Route::put('/{season}', [PlmSeasonController::class, 'update'])->name('update');
            Route::delete('/{season}', [PlmSeasonController::class, 'destroy'])->name('destroy');
        });

        // Tech Pack Types
        Route::prefix('tech-pack-types')->name('tech-pack-types.')->group(function () {
            Route::get('/', [TechPackTypeController::class, 'index'])->name('index');
            Route::post('/', [TechPackTypeController::class, 'store'])->name('store');
            Route::get('/{techPackType}', [TechPackTypeController::class, 'show'])->name('show');
            Route::put('/{techPackType}', [TechPackTypeController::class, 'update'])->name('update');
            Route::delete('/{techPackType}', [TechPackTypeController::class, 'destroy'])->name('destroy');
        });

        // Artwork Types
        Route::prefix('artwork-types')->name('artwork-types.')->group(function () {
            Route::get('/', [ArtworkTypeController::class, 'index'])->name('index');
            Route::post('/', [ArtworkTypeController::class, 'store'])->name('store');
            Route::get('/{artworkType}', [ArtworkTypeController::class, 'show'])->name('show');
            Route::put('/{artworkType}', [ArtworkTypeController::class, 'update'])->name('update');
            Route::delete('/{artworkType}', [ArtworkTypeController::class, 'destroy'])->name('destroy');
        });

        // Pattern File Types
        Route::prefix('pattern-file-types')->name('pattern-file-types.')->group(function () {
            Route::get('/', [PatternFileTypeController::class, 'index'])->name('index');
            Route::post('/', [PatternFileTypeController::class, 'store'])->name('store');
            Route::get('/{patternFileType}', [PatternFileTypeController::class, 'show'])->name('show');
            Route::put('/{patternFileType}', [PatternFileTypeController::class, 'update'])->name('update');
            Route::delete('/{patternFileType}', [PatternFileTypeController::class, 'destroy'])->name('destroy');
        });

        // File Types
        Route::prefix('file-types')->name('file-types.')->group(function () {
            Route::get('/', [FileTypeController::class, 'index'])->name('index');
            Route::post('/', [FileTypeController::class, 'store'])->name('store');
            Route::get('/{fileType}', [FileTypeController::class, 'show'])->name('show');
            Route::put('/{fileType}', [FileTypeController::class, 'update'])->name('update');
            Route::delete('/{fileType}', [FileTypeController::class, 'destroy'])->name('destroy');
        });
    });

    // ─── Clients Management (from temp_friend_module) ─────────────────────────
    Route::prefix('clients')->name('clients.')->group(function () {
        Route::get('/', [ClientController::class, 'index'])->name('index');
        Route::post('/', [ClientController::class, 'store'])->name('store');
        Route::get('/{client}', [ClientController::class, 'show'])->name('show');
        Route::put('/{client}', [ClientController::class, 'update'])->name('update');
        Route::patch('/{client}/toggle-status', [ClientController::class, 'toggleStatus'])->name('toggle-status');
        Route::delete('/{client}', [ClientController::class, 'destroy'])->name('destroy');
    });

    Route::middleware('role:super_admin')->group(function () {
        Route::resource('users', \App\Http\Controllers\UserController::class);
        Route::resource('labs', \App\Http\Controllers\LabController::class);
        // custom actions
        Route::get('shipment-management/purchase-orders',[ \App\Http\Controllers\ShipmentManagementController::class,'purchaseOrdersByBuyer'])->name('shipment-management.purchase-orders');
        Route::get('shipment-management/scan', [\App\Http\Controllers\ShipmentManagementController::class, 'scan'])->name('shipment-management.scan');
        Route::get('shipment-management/forwarder-login', [\App\Http\Controllers\ShipmentManagementController::class, 'forwarderLogin'])->name('shipment-management.forwarder-login');
        Route::resource('shipment-management', \App\Http\Controllers\ShipmentManagementController::class);

        Route::resource('roles', \App\Http\Controllers\RoleController::class);
        Route::get('permissions/grid', [\App\Http\Controllers\RoleController::class, 'grid'])->name('permissions.grid');
        Route::post('permissions/assign', [\App\Http\Controllers\RoleController::class, 'assignPrivileges'])->name('permissions.assign');
        Route::post('permissions/revoke', [\App\Http\Controllers\RoleController::class, 'revokePrivileges'])->name('permissions.revoke');
        Route::resource('cpsc-tokens', \App\Http\Controllers\CpscTokenController::class)->only(['index', 'store', 'destroy']);

        //Activity Logs
        Route::get('activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');

        Route::resource('standards', \App\Http\Controllers\StandardController::class);
        Route::resource('standard-stages', \App\Http\Controllers\StandardStageController::class);

        Route::resource('certificates', \App\Http\Controllers\CertificateController::class)->except(['index']);
        Route::post('certificates/{certificate}/renewals', [\App\Http\Controllers\RenewalProcessController::class, 'store'])->name('renewals.store');
        Route::post('renewals/{renewal}/stage', [\App\Http\Controllers\RenewalProcessController::class, 'updateStage'])->name('renewals.update_stage');
        Route::post('standard-stages/reorder', [\App\Http\Controllers\StandardStageController::class, 'reorder'])
            ->name('standard-stages.reorder');

        Route::get('rfid-generation', [\App\Http\Controllers\RfidGenerationController::class, 'index'])->name('rfid-generation.index');
        Route::get('rfid-generation/export', [\App\Http\Controllers\RfidGenerationController::class, 'export'])->name('rfid-generation.export');

        Route::prefix('master-data')->name('master-data.')->group(function () {
            Route::resource('seasons', \App\Http\Controllers\SeasonController::class);
        });
    });

    // Production Orders redirect based on role
    Route::middleware('role:super_admin|factory')->group(function () {
        Route::get('prod-orders', function () {
            return auth()->user()?->isFactory()
                ? redirect()->route('factory-portal.index')
                : redirect()->route('purchase-orders.index');
        })->name('prod-orders.index');
    });

    // Routes shared between super_admin and lab
    Route::middleware('role:super_admin|lab')->group(function () {
        Route::resource('factories', \App\Http\Controllers\FactoryController::class);

        // Stafford CRUD Routes
        Route::get('stafford-certs/report-file', [\App\Http\Controllers\StaffordQualificationController::class, 'viewReportFile'])->name('stafford-certs.view-report-file');
        Route::get('stafford-certs/{stafford_cert}/export-pdf', [\App\Http\Controllers\StaffordQualificationController::class, 'exportPdf'])->name('stafford-certs.export-pdf');
        Route::post('stafford-certs/export-bulk-pdf', [\App\Http\Controllers\StaffordQualificationController::class, 'exportBulkPdf'])->name('stafford-certs.export-bulk-pdf');
        Route::post('stafford-certs/bulk-action', [\App\Http\Controllers\StaffordQualificationController::class, 'bulkAction'])->name('stafford-certs.bulk-action');
        Route::post('stafford-certs/{stafford_cert}/reject', [\App\Http\Controllers\StaffordQualificationController::class, 'reject'])->name('stafford-certs.reject');
        Route::get('stafford-certs/erp-po-lookup', [\App\Http\Controllers\StaffordQualificationController::class, 'eravPoLookup'])->name('stafford-certs.erp-po-lookup');
        Route::get('stafford-certs/erp-po-search', [\App\Http\Controllers\StaffordQualificationController::class, 'eravPoSearch'])->name('stafford-certs.erp-po-search');
        Route::resource('stafford-certs', \App\Http\Controllers\StaffordQualificationController::class)
            ->except(['create', 'edit'])
            ->names([
                'index' => 'stafford-certs.index',
                'store' => 'stafford-certs.store',
                'show' => 'stafford-certs.show',
                'update' => 'stafford-certs.update',
                'destroy' => 'stafford-certs.destroy',
            ]);
        // Test Report Types CRUD
        Route::resource('test-report-types', \App\Http\Controllers\TestReportTypeController::class);
        // Upload report file (accessible by both super_admin and lab)
        Route::post('stafford-certs/upload-report-file', [\App\Http\Controllers\StaffordQualificationController::class, 'uploadReportFile'])->name('stafford-certs.upload-report-file');
    });

    // Laboratory specific routes
    Route::middleware('role:lab')->prefix('lab')->name('lab.')->group(function () {
        Route::get('pos', [\App\Http\Controllers\LabPoController::class, 'index'])->name('pos.index');
        Route::get('pos/data', [\App\Http\Controllers\LabPoController::class, 'poData'])->name('pos.data');
        Route::get('completed-tasks', [\App\Http\Controllers\LabPoController::class, 'completedTasks'])->name('tasks.completed');
        Route::put('tasks/{id}', [\App\Http\Controllers\LabPoController::class, 'updateTask'])->name('tasks.update');
        Route::post('upload-report-file', [\App\Http\Controllers\LabPoController::class, 'uploadReportFile'])->name('upload-report-file');
    });

    // Stafford Order Allocations
    Route::middleware('role:super_admin')->group(function () {
        Route::get('order-allocations', function () {
            return redirect()->route('purchase-orders.index');
        })->name('order-allocations.index');
    });

    // Factory Portal - PO creation and factory dashboard
    Route::middleware('role:factory')->group(function () {
        Route::get('factory-portal', [\App\Http\Controllers\FactoryPortalController::class, 'index'])->name('factory-portal.index');
        Route::post('factory-portal/{id}/store-po', [\App\Http\Controllers\FactoryPortalController::class, 'storePurchaseOrder'])->name('factory-portal.store-po');
    });

    // View-Only for Orders & Purchase Orders
    Route::middleware('role:factory|super_admin')->group(function () {
        Route::get('factory-portal/{id}', [\App\Http\Controllers\FactoryPortalController::class, 'show'])->name('factory-portal.show');

        // Purchase Orders
        Route::get('purchase-orders', [\App\Http\Controllers\PurchaseOrderController::class, 'index'])->name('purchase-orders.index');
        Route::get('purchase-orders/{id}', [\App\Http\Controllers\PurchaseOrderController::class, 'show'])->name('purchase-orders.show');
    });
});

// Fallback route to safely stream storage files if symlink is unavailable
Route::get('storage/{path}', function ($path) {
    $cleanPath = str_replace(['..', '\\'], ['', '/'], $path);
    $cleanPath = ltrim($cleanPath, '/');

    $candidates = [
        storage_path('app/public/' . $cleanPath),
        storage_path('app/public/lab_reports/' . basename($cleanPath)),
        storage_path('app/' . $cleanPath),
        public_path('storage/' . $cleanPath),
    ];

    $filePath = null;
    foreach ($candidates as $candidate) {
        if (file_exists($candidate) && is_file($candidate)) {
            $filePath = $candidate;
            break;
        }
    }

    if (!$filePath) {
        abort(404, 'File not found in storage: ' . htmlspecialchars(basename($cleanPath)));
    }

    $mimeType = mime_content_type($filePath) ?: 'application/octet-stream';
    return response()->file($filePath, [
        'Content-Type' => $mimeType,
        'Content-Disposition' => 'inline; filename="' . basename($filePath) . '"',
    ]);
})->where('path', '.*');

require __DIR__.'/auth.php';

