<?php

namespace App\Http\Controllers\Masters;

use App\Http\Controllers\Controller;
use App\Models\Scale;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class ScaleController extends Controller
{
    /**
     * Display a listing of scales.
     * Handles both initial load and Yajra DataTables AJAX requests.
     */
    public function index(Request $request): View|JsonResponse
    {
        Gate::authorize('view-scales');

        if ($request->ajax() || $request->has('draw')) {
            $scales = Scale::query();

            if ($request->filled('status_filter')) {
                $scales->where('status', $request->status_filter);
            }

            return DataTables::eloquent($scales)
                ->addColumn('status_badge', function ($row) {
                    return statusBadge($row->status);
                })
                ->rawColumns(['status_badge'])
                ->make(true);
        }

        return view('masters.scales.index');
    }

    /**
     * Store a newly created scale in storage.
     */
    public function store(Request $request): JsonResponse
    {
        Gate::authorize('create-scales');

        try {
            $validated = $request->validate([
                'name'             => 'required|string|max:100|unique:scales,name',
                'percentage_value' => 'required|numeric|min:0|max:500',
                'status'           => 'required|in:Active,Inactive',
            ]);

            $scale = Scale::create($validated);

            activity()
                ->causedBy(auth()->user())
                ->performedOn($scale)
                ->withProperties(['name' => $scale->name, 'percentage_value' => $scale->percentage_value, 'status' => $scale->status])
                ->log('Scale Created');

            return response()->json([
                'success' => true,
                'message' => "Scale '{$scale->name}' created successfully!",
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create scale: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified scale (AJAX fetch for edit).
     */
    public function show(Scale $scale): JsonResponse
    {
        Gate::authorize('view-scales');

        return response()->json([
            'success' => true,
            'scale'   => $scale,
        ]);
    }

    /**
     * Update the specified scale in storage.
     */
    public function update(Request $request, Scale $scale): JsonResponse
    {
        Gate::authorize('edit-scales');

        try {
            $validated = $request->validate([
                'name'             => 'required|string|max:100|unique:scales,name,' . $scale->id,
                'percentage_value' => 'required|numeric|min:0|max:500',
                'status'           => 'required|in:Active,Inactive',
            ]);

            $scale->update($validated);

            activity()
                ->causedBy(auth()->user())
                ->performedOn($scale)
                ->withProperties(['name' => $scale->name, 'percentage_value' => $scale->percentage_value, 'status' => $scale->status])
                ->log('Scale Updated');

            return response()->json([
                'success' => true,
                'message' => "Scale '{$scale->name}' updated successfully!",
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update scale: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified scale from storage.
     */
    public function destroy(Scale $scale): JsonResponse
    {
        Gate::authorize('delete-scales');

        try {
            // Check if scale is in use by any customer PO item or bundle
            $poInUse = \DB::table('customer_po_items')->where('scale_id', $scale->id)->exists();
            $bundleInUse = \DB::table('bundles')->where('scale_id', $scale->id)->exists();

            if ($poInUse || $bundleInUse) {
                return response()->json([
                    'success' => false,
                    'message' => "Cannot delete Scale '{$scale->name}' because it is currently assigned to one or more PO items or production bundles.",
                ], 422);
            }

            $scaleName = $scale->name;
            $scale->delete();

            activity()
                ->causedBy(auth()->user())
                ->performedOn($scale)
                ->log("Scale '{$scaleName}' Deleted");

            return response()->json([
                'success' => true,
                'message' => "Scale '{$scaleName}' deleted successfully!",
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete scale: ' . $e->getMessage(),
            ], 500);
        }
    }
}
