<?php

namespace App\Http\Controllers\Masters;

use App\Http\Controllers\Controller;
use App\Models\PatternFileType;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class PatternFileTypeController extends Controller
{
    /**
     * Display a listing of pattern file types.
     */
    public function index(Request $request): View|JsonResponse
    {
        if (Gate::has('view-pattern-file-types')) {
            Gate::authorize('view-pattern-file-types');
        }

        if ($request->ajax() || $request->has('draw')) {
            $types = PatternFileType::query()->withCount('samples');

            if ($request->filled('status_filter')) {
                $types->where('status', $request->status_filter);
            }

            return DataTables::eloquent($types)
                ->addColumn('status_badge', function ($row) {
                    return function_exists('statusBadge') ? statusBadge($row->status) : $row->status;
                })
                ->addColumn('samples_count', function ($row) {
                    return '<span class="badge badge-light-primary fw-bold">' . $row->samples_count . '</span>';
                })
                ->rawColumns(['status_badge', 'samples_count'])
                ->make(true);
        }

        return view('masters.pattern-file-types.index');
    }

    /**
     * Store a newly created pattern file type in storage.
     */
    public function store(Request $request): JsonResponse
    {
        if (Gate::has('create-pattern-file-types')) {
            Gate::authorize('create-pattern-file-types');
        }

        try {
            $validated = $request->validate([
                'name'        => 'required|string|max:100|unique:pattern_file_types,name',
                'code'        => 'nullable|string|max:50',
                'description' => 'nullable|string',
                'status'      => 'required|in:Active,Inactive',
            ]);

            $type = PatternFileType::create($validated);

            if (function_exists('activity')) {
                activity()
                    ->causedBy(auth()->user())
                    ->performedOn($type)
                    ->withProperties(['name' => $type->name, 'code' => $type->code, 'status' => $type->status])
                    ->log('Pattern File Type Created');
            }

            return response()->json([
                'success'           => true,
                'message'           => "Pattern File Type '{$type->name}' created successfully!",
                'pattern_file_type' => $type,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create pattern file type: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified pattern file type.
     */
    public function show(PatternFileType $patternFileType): JsonResponse
    {
        if (Gate::has('view-pattern-file-types')) {
            Gate::authorize('view-pattern-file-types');
        }

        return response()->json([
            'success'           => true,
            'pattern_file_type' => $patternFileType,
        ]);
    }

    /**
     * Update the specified pattern file type in storage.
     */
    public function update(Request $request, PatternFileType $patternFileType): JsonResponse
    {
        if (Gate::has('edit-pattern-file-types')) {
            Gate::authorize('edit-pattern-file-types');
        }

        try {
            $validated = $request->validate([
                'name'        => 'required|string|max:100|unique:pattern_file_types,name,' . $patternFileType->id,
                'code'        => 'nullable|string|max:50',
                'description' => 'nullable|string',
                'status'      => 'required|in:Active,Inactive',
            ]);

            $patternFileType->update($validated);

            if (function_exists('activity')) {
                activity()
                    ->causedBy(auth()->user())
                    ->performedOn($patternFileType)
                    ->withProperties(['name' => $patternFileType->name, 'code' => $patternFileType->code, 'status' => $patternFileType->status])
                    ->log('Pattern File Type Updated');
            }

            return response()->json([
                'success'           => true,
                'message'           => "Pattern File Type '{$patternFileType->name}' updated successfully!",
                'pattern_file_type' => $patternFileType,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update pattern file type: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified pattern file type from storage.
     */
    public function destroy(PatternFileType $patternFileType): JsonResponse
    {
        if (Gate::has('delete-pattern-file-types')) {
            Gate::authorize('delete-pattern-file-types');
        }

        try {
            $inUse = $patternFileType->samples()->exists();
            if ($inUse) {
                return response()->json([
                    'success' => false,
                    'message' => "Cannot delete Pattern File Type '{$patternFileType->name}' because it is assigned to one or more style samples.",
                ], 422);
            }

            $typeName = $patternFileType->name;
            $patternFileType->delete();

            if (function_exists('activity')) {
                activity()
                    ->causedBy(auth()->user())
                    ->performedOn($patternFileType)
                    ->log("Pattern File Type '{$typeName}' Deleted");
            }

            return response()->json([
                'success' => true,
                'message' => "Pattern File Type '{$typeName}' deleted successfully!",
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete pattern file type: ' . $e->getMessage(),
            ], 500);
        }
    }
}
