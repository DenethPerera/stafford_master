<?php

namespace App\Http\Controllers\Masters;

use App\Http\Controllers\Controller;
use App\Models\Season;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class SeasonController extends Controller
{
    /**
     * Display a listing of seasons.
     */
    public function index(Request $request): View|JsonResponse
    {
        if (Gate::has('view-seasons')) {
            Gate::authorize('view-seasons');
        }

        if ($request->ajax() || $request->has('draw')) {
            $seasons = Season::query()->withCount('styles');

            if ($request->filled('status_filter')) {
                $seasons->where('status', $request->status_filter);
            }

            return DataTables::eloquent($seasons)
                ->addColumn('status_badge', function ($row) {
                    return function_exists('statusBadge') ? statusBadge($row->status) : $row->status;
                })
                ->addColumn('styles_count', function ($row) {
                    return '<span class="badge badge-light-primary fw-bold">' . $row->styles_count . '</span>';
                })
                ->rawColumns(['status_badge', 'styles_count'])
                ->make(true);
        }

        return view('masters.seasons.index');
    }

    /**
     * Store a newly created season in storage.
     */
    public function store(Request $request): JsonResponse
    {
        if (Gate::has('create-seasons')) {
            Gate::authorize('create-seasons');
        }

        try {
            $validated = $request->validate([
                'name'        => 'required|string|max:100|unique:seasons,name',
                'code'        => 'nullable|string|max:50',
                'description' => 'nullable|string',
                'status'      => 'required|in:Active,Inactive',
            ]);

            $season = Season::create($validated);

            if (function_exists('activity')) {
                activity()
                    ->causedBy(auth()->user())
                    ->performedOn($season)
                    ->withProperties(['name' => $season->name, 'code' => $season->code, 'status' => $season->status])
                    ->log('Season Created');
            }

            return response()->json([
                'success' => true,
                'message' => "Season '{$season->name}' created successfully!",
                'season'  => $season,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create season: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified season.
     */
    public function show(Season $season): JsonResponse
    {
        if (Gate::has('view-seasons')) {
            Gate::authorize('view-seasons');
        }

        return response()->json([
            'success' => true,
            'season'  => $season,
        ]);
    }

    /**
     * Update the specified season in storage.
     */
    public function update(Request $request, Season $season): JsonResponse
    {
        if (Gate::has('edit-seasons')) {
            Gate::authorize('edit-seasons');
        }

        try {
            $validated = $request->validate([
                'name'        => 'required|string|max:100|unique:seasons,name,' . $season->id,
                'code'        => 'nullable|string|max:50',
                'description' => 'nullable|string',
                'status'      => 'required|in:Active,Inactive',
            ]);

            $oldName = $season->name;
            $season->update($validated);

            // If name changed, update style references
            if ($oldName !== $season->name) {
                DB::table('styles')->where('season', $oldName)->update(['season' => $season->name]);
            }

            if (function_exists('activity')) {
                activity()
                    ->causedBy(auth()->user())
                    ->performedOn($season)
                    ->withProperties(['name' => $season->name, 'code' => $season->code, 'status' => $season->status])
                    ->log('Season Updated');
            }

            return response()->json([
                'success' => true,
                'message' => "Season '{$season->name}' updated successfully!",
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update season: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified season from storage.
     */
    public function destroy(Season $season): JsonResponse
    {
        if (Gate::has('delete-seasons')) {
            Gate::authorize('delete-seasons');
        }

        try {
            $inUse = DB::table('styles')->where('season', $season->name)->exists();
            if ($inUse) {
                return response()->json([
                    'success' => false,
                    'message' => "Cannot delete Season '{$season->name}' because it is currently assigned to one or more styles.",
                ], 422);
            }

            $seasonName = $season->name;
            $season->delete();

            if (function_exists('activity')) {
                activity()
                    ->causedBy(auth()->user())
                    ->performedOn($season)
                    ->log("Season '{$seasonName}' Deleted");
            }

            return response()->json([
                'success' => true,
                'message' => "Season '{$seasonName}' deleted successfully!",
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete season: ' . $e->getMessage(),
            ], 500);
        }
    }
}
