<?php

namespace App\Http\Controllers\Masters;

use App\Http\Controllers\Controller;
use App\Models\Color;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class ColorController extends Controller
{
    /**
     * Display a listing of colors.
     * Handles both initial load and Yajra DataTables AJAX requests.
     */
    public function index(Request $request): View|JsonResponse
    {
        Gate::authorize('view-colors');

        if ($request->ajax() || $request->has('draw')) {
            $colors = Color::query();

            if ($request->filled('status_filter')) {
                $colors->where('status', $request->status_filter);
            }

            return DataTables::eloquent($colors)
                ->addColumn('status_badge', function ($row) {
                    return statusBadge($row->status);
                })
                ->rawColumns(['status_badge'])
                ->make(true);
        }

        return view('masters.colors.index');
    }

    /**
     * Store a newly created color in storage.
     */
    public function store(Request $request): JsonResponse
    {
        Gate::authorize('create-colors');

        try {
            $validated = $request->validate([
                'name'   => 'required|string|max:100|unique:colors,name',
                'status' => 'required|in:Active,Inactive',
            ]);

            $color = Color::create($validated);

            activity()
                ->causedBy(auth()->user())
                ->performedOn($color)
                ->withProperties(['name' => $color->name, 'status' => $color->status])
                ->log('Color Created');

            return response()->json([
                'success' => true,
                'message' => "Color '{$color->name}' created successfully!",
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create color: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified color (AJAX fetch for edit).
     */
    public function show(Color $color): JsonResponse
    {
        Gate::authorize('view-colors');

        return response()->json([
            'success' => true,
            'color'   => $color,
        ]);
    }

    /**
     * Update the specified color in storage.
     */
    public function update(Request $request, Color $color): JsonResponse
    {
        Gate::authorize('edit-colors');

        try {
            $validated = $request->validate([
                'name'   => 'required|string|max:100|unique:colors,name,' . $color->id,
                'status' => 'required|in:Active,Inactive',
            ]);

            $color->update($validated);

            activity()
                ->causedBy(auth()->user())
                ->performedOn($color)
                ->withProperties(['name' => $color->name, 'status' => $color->status])
                ->log('Color Updated');

            return response()->json([
                'success' => true,
                'message' => "Color '{$color->name}' updated successfully!",
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update color: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified color from storage.
     */
    public function destroy(Color $color): JsonResponse
    {
        Gate::authorize('delete-colors');

        try {
            // Check if color is in use by any bundle
            $inUse = \DB::table('bundles')->where('color_id', $color->id)->exists();
            if ($inUse) {
                return response()->json([
                    'success' => false,
                    'message' => "Cannot delete Color '{$color->name}' because it is currently assigned to one or more bundles.",
                ], 422);
            }

            $colorName = $color->name;
            $color->delete();

            activity()
                ->causedBy(auth()->user())
                ->performedOn($color)
                ->log("Color '{$colorName}' Deleted");

            return response()->json([
                'success' => true,
                'message' => "Color '{$colorName}' deleted successfully!",
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete color: ' . $e->getMessage(),
            ], 500);
        }
    }
}
