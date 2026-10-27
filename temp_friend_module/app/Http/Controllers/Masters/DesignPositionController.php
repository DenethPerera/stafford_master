<?php

namespace App\Http\Controllers\Masters;

use App\Http\Controllers\Controller;
use App\Models\DesignPosition;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class DesignPositionController extends Controller
{
    /**
     * Display a listing of design positions.
     * Handles both initial load and Yajra DataTables AJAX requests.
     */
    public function index(Request $request): View|JsonResponse
    {
        Gate::authorize('view-design-positions');

        if ($request->ajax() || $request->has('draw')) {
            $designPositions = DesignPosition::query();

            if ($request->filled('status_filter')) {
                $designPositions->where('status', $request->status_filter);
            }

            return DataTables::eloquent($designPositions)
                ->addColumn('status_badge', function ($row) {
                    return statusBadge($row->status);
                })
                ->rawColumns(['status_badge'])
                ->make(true);
        }

        return view('masters.design-positions.index');
    }

    /**
     * Store a newly created design position in storage.
     */
    public function store(Request $request): JsonResponse
    {
        Gate::authorize('create-design-positions');

        try {
            $validated = $request->validate([
                'name'   => 'required|string|max:100|unique:design_positions,name',
                'status' => 'required|in:Active,Inactive',
            ]);

            $designPosition = DesignPosition::create($validated);

            activity()
                ->causedBy(auth()->user())
                ->performedOn($designPosition)
                ->withProperties(['name' => $designPosition->name, 'status' => $designPosition->status])
                ->log('Design Position Created');

            return response()->json([
                'success' => true,
                'message' => "Design Position '{$designPosition->name}' created successfully!",
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create design position: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified design position (AJAX fetch for edit).
     */
    public function show(DesignPosition $designPosition): JsonResponse
    {
        Gate::authorize('view-design-positions');

        return response()->json([
            'success' => true,
            'designPosition' => $designPosition,
        ]);
    }

    /**
     * Update the specified design position in storage.
     */
    public function update(Request $request, DesignPosition $designPosition): JsonResponse
    {
        Gate::authorize('edit-design-positions');

        try {
            $validated = $request->validate([
                'name'   => 'required|string|max:100|unique:design_positions,name,' . $designPosition->id,
                'status' => 'required|in:Active,Inactive',
            ]);

            $designPosition->update($validated);

            activity()
                ->causedBy(auth()->user())
                ->performedOn($designPosition)
                ->withProperties(['name' => $designPosition->name, 'status' => $designPosition->status])
                ->log('Design Position Updated');

            return response()->json([
                'success' => true,
                'message' => "Design Position '{$designPosition->name}' updated successfully!",
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update design position: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified design position from storage.
     */
    public function destroy(DesignPosition $designPosition): JsonResponse
    {
        Gate::authorize('delete-design-positions');

        try {
            // Check if design position is in use by any bundle
            $inUse = \DB::table('bundles')->where('design_position_id', $designPosition->id)->exists();
            if ($inUse) {
                return response()->json([
                    'success' => false,
                    'message' => "Cannot delete Design Position '{$designPosition->name}' because it is currently assigned to one or more bundles.",
                ], 422);
            }

            $designPositionName = $designPosition->name;
            $designPosition->delete();

            activity()
                ->causedBy(auth()->user())
                ->performedOn($designPosition)
                ->log("Design Position '{$designPositionName}' Deleted");

            return response()->json([
                'success' => true,
                'message' => "Design Position '{$designPositionName}' deleted successfully!",
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete design position: ' . $e->getMessage(),
            ], 500);
        }
    }
}
