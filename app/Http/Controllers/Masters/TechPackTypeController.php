<?php

namespace App\Http\Controllers\Masters;

use App\Http\Controllers\Controller;
use App\Models\TechPackType;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class TechPackTypeController extends Controller
{
    /**
     * Display a listing of tech pack types.
     */
    public function index(Request $request): View|JsonResponse
    {
        if (Gate::has('view-tech-pack-types')) {
            Gate::authorize('view-tech-pack-types');
        }

        if ($request->ajax() || $request->has('draw')) {
            $types = TechPackType::query()->withCount('documents');

            if ($request->filled('status_filter')) {
                $types->where('status', $request->status_filter);
            }

            return DataTables::eloquent($types)
                ->addColumn('status_badge', function ($row) {
                    return function_exists('statusBadge') ? statusBadge($row->status) : $row->status;
                })
                ->addColumn('documents_count', function ($row) {
                    return '<span class="badge badge-light-primary fw-bold">' . $row->documents_count . '</span>';
                })
                ->rawColumns(['status_badge', 'documents_count'])
                ->make(true);
        }

        return view('masters.tech-pack-types.index');
    }

    /**
     * Store a newly created tech pack type in storage.
     */
    public function store(Request $request): JsonResponse
    {
        if (Gate::has('create-tech-pack-types')) {
            Gate::authorize('create-tech-pack-types');
        }

        try {
            $validated = $request->validate([
                'name'        => 'required|string|max:100|unique:tech_pack_types,name',
                'code'        => 'nullable|string|max:50',
                'description' => 'nullable|string',
                'status'      => 'required|in:Active,Inactive',
            ]);

            $type = TechPackType::create($validated);

            if (function_exists('activity')) {
                activity()
                    ->causedBy(auth()->user())
                    ->performedOn($type)
                    ->withProperties(['name' => $type->name, 'code' => $type->code, 'status' => $type->status])
                    ->log('Tech Pack Type Created');
            }

            return response()->json([
                'success' => true,
                'message' => "Tech Pack Type '{$type->name}' created successfully!",
                'tech_pack_type' => $type,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create tech pack type: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified tech pack type.
     */
    public function show(TechPackType $techPackType): JsonResponse
    {
        if (Gate::has('view-tech-pack-types')) {
            Gate::authorize('view-tech-pack-types');
        }

        return response()->json([
            'success' => true,
            'tech_pack_type' => $techPackType,
        ]);
    }

    /**
     * Update the specified tech pack type in storage.
     */
    public function update(Request $request, TechPackType $techPackType): JsonResponse
    {
        if (Gate::has('edit-tech-pack-types')) {
            Gate::authorize('edit-tech-pack-types');
        }

        try {
            $validated = $request->validate([
                'name'        => 'required|string|max:100|unique:tech_pack_types,name,' . $techPackType->id,
                'code'        => 'nullable|string|max:50',
                'description' => 'nullable|string',
                'status'      => 'required|in:Active,Inactive',
            ]);

            $oldName = $techPackType->name;
            $techPackType->update($validated);

            if ($oldName !== $techPackType->name) {
                DB::table('style_tech_pack_documents')
                    ->where('tech_pack_type_id', $techPackType->id)
                    ->update(['type_name' => $techPackType->name]);
            }

            if (function_exists('activity')) {
                activity()
                    ->causedBy(auth()->user())
                    ->performedOn($techPackType)
                    ->withProperties(['name' => $techPackType->name, 'code' => $techPackType->code, 'status' => $techPackType->status])
                    ->log('Tech Pack Type Updated');
            }

            return response()->json([
                'success' => true,
                'message' => "Tech Pack Type '{$techPackType->name}' updated successfully!",
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update tech pack type: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified tech pack type from storage.
     */
    public function destroy(TechPackType $techPackType): JsonResponse
    {
        if (Gate::has('delete-tech-pack-types')) {
            Gate::authorize('delete-tech-pack-types');
        }

        try {
            $inUse = DB::table('style_tech_pack_documents')
                ->where('tech_pack_type_id', $techPackType->id)
                ->exists();

            if ($inUse) {
                return response()->json([
                    'success' => false,
                    'message' => "Cannot delete Tech Pack Type '{$techPackType->name}' because it has uploaded documents attached to one or more styles.",
                ], 422);
            }

            $typeName = $techPackType->name;
            $techPackType->delete();

            if (function_exists('activity')) {
                activity()
                    ->causedBy(auth()->user())
                    ->performedOn($techPackType)
                    ->log("Tech Pack Type '{$typeName}' Deleted");
            }

            return response()->json([
                'success' => true,
                'message' => "Tech Pack Type '{$typeName}' deleted successfully!",
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete tech pack type: ' . $e->getMessage(),
            ], 500);
        }
    }
}
