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
use App\Http\Controllers\Masters\SeasonController;
use App\Http\Controllers\Masters\SizeController;
use App\Http\Controllers\Masters\TechPackTypeController;
use App\Http\Controllers\StyleController;

// ─── Public Routes ───────────────────────────────────────────────────────────
Route::get('/', function () {
    return redirect()->route('login');
});

// Auth routes (provided by Laravel Breeze / authentication scaffolding)
require __DIR__ . '/auth.php';

// ─── Authenticated Routes ────────────────────────────────────────────────────
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::view('/dashboard', 'dashboard')->name('dashboard');

    // ─── PLM Styles Workspace (Primary Hub) ──────────────────────────────────
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
        Route::patch('/{style}/special-instructions', [StyleController::class, 'updateSpecialInstructions'])->name('update-special-instructions');

        // Sub-resource AJAX endpoints for 10 PLM Workspace Tabs
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
    });

    // Legacy Redirect for /designs
    Route::get('designs', fn() => redirect()->route('styles.index'));

    // ─── Master Data Management ──────────────────────────────────────────────
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

        // Seasons
        Route::prefix('seasons')->name('seasons.')->group(function () {
            Route::get('/', [SeasonController::class, 'index'])->name('index');
            Route::post('/', [SeasonController::class, 'store'])->name('store');
            Route::get('/{season}', [SeasonController::class, 'show'])->name('show');
            Route::put('/{season}', [SeasonController::class, 'update'])->name('update');
            Route::delete('/{season}', [SeasonController::class, 'destroy'])->name('destroy');
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

        // Styles Master (Alias under Masters)
        Route::prefix('styles')->group(function () {
            Route::get('/', [StyleController::class, 'index']);
            Route::get('/create', [StyleController::class, 'create']);
            Route::post('/', [StyleController::class, 'store']);
            Route::get('/{style}', [StyleController::class, 'show']);
            Route::get('/{style}/edit', [StyleController::class, 'edit']);
            Route::put('/{style}', [StyleController::class, 'update']);
        });
    });

    // ─── Clients Management ──────────────────────────────────────────────────
    Route::prefix('clients')->name('clients.')->group(function () {
        Route::get('/', [ClientController::class, 'index'])->name('index');
        Route::post('/', [ClientController::class, 'store'])->name('store');
        Route::get('/{client}', [ClientController::class, 'show'])->name('show');
        Route::put('/{client}', [ClientController::class, 'update'])->name('update');
        Route::patch('/{client}/toggle-status', [ClientController::class, 'toggleStatus'])->name('toggle-status');
        Route::delete('/{client}', [ClientController::class, 'destroy'])->name('destroy');
    });

    // ─── Activity Logs ────────────────────────────────────────────────────────
    Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');

    // ─── User Accounts / Privileges ──────────────────────────────────────────
    if (file_exists(__DIR__ . '/useraccount.php')) {
        require __DIR__ . '/useraccount.php';
    }
});
