<?php

namespace App\Http\Controllers\Masters;

use App\Http\Controllers\Controller;
use App\Models\Construction;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class ConstructionController extends Controller
{
 
     // Display a listing of constructions.
     
    public function index(Request $request): View|JsonResponse
    {
        if (Gate::has('view-constructions')) {
            Gate::authorize('view-constructions');
        }

        if ($request->ajax() || $request->has('draw')) {
            $constructions = Construction::query();

            if ($request->filled('status_filter')) {
                $constructions->where('status', $request->status_filter);
            }

            return DataTables::eloquent($constructions)
                ->addColumn('status_badge', function ($row) {
                    return function_exists('statusBadge') ? statusBadge($row->status) : $row->status;
                })
                ->rawColumns(['status_badge'])
                ->make(true);
        }

        return view('masters.constructions.index');
    }

    
     // Store a newly created construction in storage.

    public function store(Request $request): JsonResponse
    {
        if (Gate::has('create-constructions')) {
            Gate::authorize('create-constructions');
        }

        try {
            $validated = $request->validate([
                'name'   => 'required|string|max:100|unique:constructions,name',
                'code'   => 'nullable|string|max:50',
                'status' => 'required|in:Active,Inactive',
            ]);

            $construction = Construction::create($validated);

            if (function_exists('activity')) {
                activity()
                    ->causedBy(auth()->user())
                    ->performedOn($construction)
                    ->withProperties(['name' => $construction->name, 'code' => $construction->code, 'status' => $construction->status])
                    ->log('Construction Created');
            }

            return response()->json([
                'success' => true,
                'message' => "Construction '{$construction->name}' created successfully!",
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create construction: ' . $e->getMessage(),
            ], 500);
        }
    }

    
     // Display the specified construction.
     
    public function show(Construction $construction): JsonResponse
    {
        if (Gate::has('view-constructions')) {
            Gate::authorize('view-constructions');
        }

        return response()->json([
            'success' => true,
            'construction' => $construction,
        ]);
    }


     // Update the specified construction in storage.
     
    public function update(Request $request, Construction $construction): JsonResponse
    {
        if (Gate::has('edit-constructions')) {
            Gate::authorize('edit-constructions');
        }

        try {
            $validated = $request->validate([
                'name'   => 'required|string|max:100|unique:constructions,name,' . $construction->id,
                'code'   => 'nullable|string|max:50',
                'status' => 'required|in:Active,Inactive',
            ]);

            $oldName = $construction->name;
            $construction->update($validated);

            // If name changed, optionally update style references
            if ($oldName !== $construction->name) {
                DB::table('styles')->where('construction', $oldName)->update(['construction' => $construction->name]);
            }

            if (function_exists('activity')) {
                activity()
                    ->causedBy(auth()->user())
                    ->performedOn($construction)
                    ->withProperties(['name' => $construction->name, 'code' => $construction->code, 'status' => $construction->status])
                    ->log('Construction Updated');
            }

            return response()->json([
                'success' => true,
                'message' => "Construction '{$construction->name}' updated successfully!",
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update construction: ' . $e->getMessage(),
            ], 500);
        }
    }


     // Remove the specified construction from storage.
     
    public function destroy(Construction $construction): JsonResponse
    {
        if (Gate::has('delete-constructions')) {
            Gate::authorize('delete-constructions');
        }

        try {
            $inUse = DB::table('styles')->where('construction', $construction->name)->exists();
            if ($inUse) {
                return response()->json([
                    'success' => false,
                    'message' => "Cannot delete Construction '{$construction->name}' because it is currently assigned to one or more styles.",
                ], 422);
            }

            $constructionName = $construction->name;
            $construction->delete();

            if (function_exists('activity')) {
                activity()
                    ->causedBy(auth()->user())
                    ->performedOn($construction)
                    ->log("Construction '{$constructionName}' Deleted");
            }

            return response()->json([
                'success' => true,
                'message' => "Construction '{$constructionName}' deleted successfully!",
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete construction: ' . $e->getMessage(),
            ], 500);
        }
    }
}
