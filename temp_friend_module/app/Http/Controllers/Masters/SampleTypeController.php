<?php

namespace App\Http\Controllers\Masters;

use App\Http\Controllers\Controller;
use App\Models\SampleType;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class SampleTypeController extends Controller
{
    /**
     * Display a listing of sample types.
     */
    public function index(Request $request): View|JsonResponse
    {
        if (Gate::has('view-sample-types')) {
            Gate::authorize('view-sample-types');
        }

        if ($request->ajax() || $request->has('draw')) {
            $sampleTypes = SampleType::query()->withCount('samples');

            if ($request->filled('status_filter')) {
                $sampleTypes->where('status', $request->status_filter);
            }

            return DataTables::eloquent($sampleTypes)
                ->addColumn('status_badge', function ($row) {
                    return function_exists('statusBadge') ? statusBadge($row->status) : $row->status;
                })
                ->addColumn('samples_count', function ($row) {
                    return '<span class="badge badge-light-primary fw-bold">' . $row->samples_count . '</span>';
                })
                ->rawColumns(['status_badge', 'samples_count'])
                ->make(true);
        }

        return view('masters.sample-types.index');
    }

    /**
     * Store a newly created sample type in storage.
     */
    public function store(Request $request): JsonResponse
    {
        if (Gate::has('create-sample-types')) {
            Gate::authorize('create-sample-types');
        }

        try {
            $validated = $request->validate([
                'name'        => 'required|string|max:100|unique:sample_types,name',
                'code'        => 'nullable|string|max:50',
                'description' => 'nullable|string',
                'status'      => 'required|in:Active,Inactive',
            ]);

            $sampleType = SampleType::create($validated);

            if (function_exists('activity')) {
                activity()
                    ->causedBy(auth()->user())
                    ->performedOn($sampleType)
                    ->withProperties(['name' => $sampleType->name, 'code' => $sampleType->code, 'status' => $sampleType->status])
                    ->log('Sample Type Created');
            }

            return response()->json([
                'success' => true,
                'message' => "Sample Type '{$sampleType->name}' created successfully!",
                'sample_type' => $sampleType,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create sample type: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified sample type.
     */
    public function show(SampleType $sampleType): JsonResponse
    {
        if (Gate::has('view-sample-types')) {
            Gate::authorize('view-sample-types');
        }

        return response()->json([
            'success' => true,
            'sample_type' => $sampleType,
        ]);
    }

    /**
     * Update the specified sample type in storage.
     */
    public function update(Request $request, SampleType $sampleType): JsonResponse
    {
        if (Gate::has('edit-sample-types')) {
            Gate::authorize('edit-sample-types');
        }

        try {
            $validated = $request->validate([
                'name'        => 'required|string|max:100|unique:sample_types,name,' . $sampleType->id,
                'code'        => 'nullable|string|max:50',
                'description' => 'nullable|string',
                'status'      => 'required|in:Active,Inactive',
            ]);

            $sampleType->update($validated);

            if (function_exists('activity')) {
                activity()
                    ->causedBy(auth()->user())
                    ->performedOn($sampleType)
                    ->withProperties(['name' => $sampleType->name, 'code' => $sampleType->code, 'status' => $sampleType->status])
                    ->log('Sample Type Updated');
            }

            return response()->json([
                'success' => true,
                'message' => "Sample Type '{$sampleType->name}' updated successfully!",
                'sample_type' => $sampleType,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update sample type: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified sample type from storage.
     */
    public function destroy(SampleType $sampleType): JsonResponse
    {
        if (Gate::has('delete-sample-types')) {
            Gate::authorize('delete-sample-types');
        }

        try {
            $inUse = $sampleType->samples()->exists();
            if ($inUse) {
                return response()->json([
                    'success' => false,
                    'message' => "Cannot delete Sample Type '{$sampleType->name}' because it is assigned to one or more style samples.",
                ], 422);
            }

            $typeName = $sampleType->name;
            $sampleType->delete();

            if (function_exists('activity')) {
                activity()
                    ->causedBy(auth()->user())
                    ->performedOn($sampleType)
                    ->log("Sample Type '{$typeName}' Deleted");
            }

            return response()->json([
                'success' => true,
                'message' => "Sample Type '{$typeName}' deleted successfully!",
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete sample type: ' . $e->getMessage(),
            ], 500);
        }
    }
}
