<?php

namespace App\Http\Controllers\Masters;

use App\Http\Controllers\Controller;
use App\Models\Size;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class SizeController extends Controller
{
    /**
     * Display a listing of sizes.
     * Handles both initial load and Yajra DataTables AJAX requests.
     */
    public function index(Request $request): View|JsonResponse
    {
        Gate::authorize('view-sizes');

        if ($request->ajax() || $request->has('draw')) {
            $sizes = Size::query();

            if ($request->filled('status_filter')) {
                $sizes->where('status', $request->status_filter);
            }

            return DataTables::eloquent($sizes)
                ->addColumn('status_badge', function ($row) {
                    return statusBadge($row->status);
                })
                ->rawColumns(['status_badge'])
                ->make(true);
        }

        return view('masters.sizes.index');
    }

    /**
     * Store a newly created size in storage.
     */
    public function store(Request $request): JsonResponse
    {
        Gate::authorize('create-sizes');

        try {
            $validated = $request->validate([
                'name'   => 'required|string|max:100|unique:opma_sizes,name',
                'status' => 'required|in:Active,Inactive',
            ]);

            $size = Size::create($validated);

            activity()
                ->causedBy(auth()->user())
                ->performedOn($size)
                ->withProperties(['name' => $size->name, 'status' => $size->status])
                ->log('Size Created');

            return response()->json([
                'success' => true,
                'message' => "Size '{$size->name}' created successfully!",
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create size: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified size (AJAX fetch for edit).
     */
    public function show(Size $size): JsonResponse
    {
        Gate::authorize('view-sizes');

        return response()->json([
            'success' => true,
            'size'    => $size,
        ]);
    }

    /**
     * Update the specified size in storage.
     */
    public function update(Request $request, Size $size): JsonResponse
    {
        Gate::authorize('edit-sizes');

        try {
            $validated = $request->validate([
                'name'   => 'required|string|max:100|unique:opma_sizes,name,' . $size->id,
                'status' => 'required|in:Active,Inactive',
            ]);

            $size->update($validated);

            activity()
                ->causedBy(auth()->user())
                ->performedOn($size)
                ->withProperties(['name' => $size->name, 'status' => $size->status])
                ->log('Size Updated');

            return response()->json([
                'success' => true,
                'message' => "Size '{$size->name}' updated successfully!",
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update size: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified size from storage.
     */
    public function destroy(Size $size): JsonResponse
    {
        Gate::authorize('delete-sizes');

        try {
            // Check if size is in use by any bundle
            $inUse = \DB::table('bundles')->where('size_id', $size->id)->exists();
            if ($inUse) {
                return response()->json([
                    'success' => false,
                    'message' => "Cannot delete Size '{$size->name}' because it is currently assigned to one or more bundles.",
                ], 422);
            }

            $sizeName = $size->name;
            $size->delete();

            activity()
                ->causedBy(auth()->user())
                ->performedOn($size)
                ->log("Size '{$sizeName}' Deleted");

            return response()->json([
                'success' => true,
                'message' => "Size '{$sizeName}' deleted successfully!",
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete size: ' . $e->getMessage(),
            ], 500);
        }
    }
}
