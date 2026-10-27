<?php

namespace App\Http\Controllers\Masters;

use App\Http\Controllers\Controller;
use App\Models\ArtworkType;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class ArtworkTypeController extends Controller
{
    /**
     * Display a listing of artwork types.
     */
    public function index(Request $request): View|JsonResponse
    {
        if (Gate::has('view-artwork-types')) {
            Gate::authorize('view-artwork-types');
        }

        if ($request->ajax() || $request->has('draw')) {
            $artworkTypes = ArtworkType::query()->withCount('artworks');

            if ($request->filled('status_filter')) {
                $artworkTypes->where('status', $request->status_filter);
            }

            return DataTables::eloquent($artworkTypes)
                ->addColumn('status_badge', function ($row) {
                    return function_exists('statusBadge') ? statusBadge($row->status) : $row->status;
                })
                ->addColumn('artworks_count', function ($row) {
                    return '<span class="badge badge-light-primary fw-bold">' . $row->artworks_count . '</span>';
                })
                ->rawColumns(['status_badge', 'artworks_count'])
                ->make(true);
        }

        return view('masters.artwork-types.index');
    }

    /**
     * Store a newly created artwork type in storage.
     */
    public function store(Request $request): JsonResponse
    {
        if (Gate::has('create-artwork-types')) {
            Gate::authorize('create-artwork-types');
        }

        try {
            $validated = $request->validate([
                'name'        => 'required|string|max:100|unique:artwork_types,name',
                'code'        => 'nullable|string|max:50',
                'description' => 'nullable|string',
                'status'      => 'required|in:Active,Inactive',
            ]);

            $artworkType = ArtworkType::create($validated);

            if (function_exists('activity')) {
                activity()
                    ->causedBy(auth()->user())
                    ->performedOn($artworkType)
                    ->withProperties(['name' => $artworkType->name, 'code' => $artworkType->code, 'status' => $artworkType->status])
                    ->log('Artwork Type Created');
            }

            return response()->json([
                'success'      => true,
                'message'      => "Artwork Type '{$artworkType->name}' created successfully!",
                'artwork_type' => $artworkType,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create artwork type: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified artwork type.
     */
    public function show(ArtworkType $artworkType): JsonResponse
    {
        if (Gate::has('view-artwork-types')) {
            Gate::authorize('view-artwork-types');
        }

        return response()->json([
            'success'      => true,
            'artwork_type' => $artworkType,
        ]);
    }

    /**
     * Update the specified artwork type in storage.
     */
    public function update(Request $request, ArtworkType $artworkType): JsonResponse
    {
        if (Gate::has('edit-artwork-types')) {
            Gate::authorize('edit-artwork-types');
        }

        try {
            $validated = $request->validate([
                'name'        => 'required|string|max:100|unique:artwork_types,name,' . $artworkType->id,
                'code'        => 'nullable|string|max:50',
                'description' => 'nullable|string',
                'status'      => 'required|in:Active,Inactive',
            ]);

            $artworkType->update($validated);

            if (function_exists('activity')) {
                activity()
                    ->causedBy(auth()->user())
                    ->performedOn($artworkType)
                    ->withProperties(['name' => $artworkType->name, 'code' => $artworkType->code, 'status' => $artworkType->status])
                    ->log('Artwork Type Updated');
            }

            return response()->json([
                'success'      => true,
                'message'      => "Artwork Type '{$artworkType->name}' updated successfully!",
                'artwork_type' => $artworkType,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update artwork type: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified artwork type from storage.
     */
    public function destroy(ArtworkType $artworkType): JsonResponse
    {
        if (Gate::has('delete-artwork-types')) {
            Gate::authorize('delete-artwork-types');
        }

        try {
            $inUse = $artworkType->artworks()->exists();
            if ($inUse) {
                return response()->json([
                    'success' => false,
                    'message' => "Cannot delete Artwork Type '{$artworkType->name}' because it is assigned to one or more style artworks.",
                ], 422);
            }

            $typeName = $artworkType->name;
            $artworkType->delete();

            if (function_exists('activity')) {
                activity()
                    ->causedBy(auth()->user())
                    ->performedOn($artworkType)
                    ->log("Artwork Type '{$typeName}' Deleted");
            }

            return response()->json([
                'success' => true,
                'message' => "Artwork Type '{$typeName}' deleted successfully!",
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete artwork type: ' . $e->getMessage(),
            ], 500);
        }
    }
}
