<?php

namespace App\Http\Controllers\Masters;

use App\Http\Controllers\Controller;
use App\Models\FileType;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class FileTypeController extends Controller
{
    /**
     * Display a listing of file types.
     */
    public function index(Request $request): View|JsonResponse
    {
        if (Gate::has('view-file-types')) {
            Gate::authorize('view-file-types');
        }

        if ($request->ajax() || $request->has('draw')) {
            $types = FileType::query()->withCount('styleFiles');

            if ($request->filled('status_filter')) {
                $types->where('status', $request->status_filter);
            }

            return DataTables::eloquent($types)
                ->addColumn('status_badge', function ($row) {
                    return function_exists('statusBadge') ? statusBadge($row->status) : $row->status;
                })
                ->addColumn('files_count', function ($row) {
                    return '<span class="badge badge-light-primary fw-bold">' . $row->style_files_count . '</span>';
                })
                ->rawColumns(['status_badge', 'files_count'])
                ->make(true);
        }

        return view('masters.file-types.index');
    }

    /**
     * Store a newly created file type in storage.
     */
    public function store(Request $request): JsonResponse
    {
        if (Gate::has('create-file-types')) {
            Gate::authorize('create-file-types');
        }

        try {
            $validated = $request->validate([
                'name'        => 'required|string|max:100|unique:file_types,name',
                'code'        => 'nullable|string|max:50',
                'description' => 'nullable|string',
                'status'      => 'required|in:Active,Inactive',
            ]);

            $type = FileType::create($validated);

            if (function_exists('activity')) {
                activity()
                    ->causedBy(auth()->user())
                    ->performedOn($type)
                    ->withProperties(['name' => $type->name, 'code' => $type->code, 'status' => $type->status])
                    ->log('File Type Created');
            }

            return response()->json([
                'success'   => true,
                'message'   => "File Type '{$type->name}' created successfully!",
                'file_type' => $type,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create file type: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified file type.
     */
    public function show(FileType $fileType): JsonResponse
    {
        if (Gate::has('view-file-types')) {
            Gate::authorize('view-file-types');
        }

        return response()->json([
            'success'   => true,
            'file_type' => $fileType,
        ]);
    }

    /**
     * Update the specified file type in storage.
     */
    public function update(Request $request, FileType $fileType): JsonResponse
    {
        if (Gate::has('edit-file-types')) {
            Gate::authorize('edit-file-types');
        }

        try {
            $validated = $request->validate([
                'name'        => 'required|string|max:100|unique:file_types,name,' . $fileType->id,
                'code'        => 'nullable|string|max:50',
                'description' => 'nullable|string',
                'status'      => 'required|in:Active,Inactive',
            ]);

            $fileType->update($validated);

            if (function_exists('activity')) {
                activity()
                    ->causedBy(auth()->user())
                    ->performedOn($fileType)
                    ->withProperties(['name' => $fileType->name, 'code' => $fileType->code, 'status' => $fileType->status])
                    ->log('File Type Updated');
            }

            return response()->json([
                'success'   => true,
                'message'   => "File Type '{$fileType->name}' updated successfully!",
                'file_type' => $fileType,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update file type: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified file type from storage.
     */
    public function destroy(FileType $fileType): JsonResponse
    {
        if (Gate::has('delete-file-types')) {
            Gate::authorize('delete-file-types');
        }

        try {
            $inUse = $fileType->styleFiles()->exists();
            if ($inUse) {
                return response()->json([
                    'success' => false,
                    'message' => "Cannot delete File Type '{$fileType->name}' because it is assigned to one or more uploaded style files.",
                ], 422);
            }

            $typeName = $fileType->name;
            $fileType->delete();

            if (function_exists('activity')) {
                activity()
                    ->causedBy(auth()->user())
                    ->performedOn($fileType)
                    ->log("File Type '{$typeName}' Deleted");
            }

            return response()->json([
                'success' => true,
                'message' => "File Type '{$typeName}' deleted successfully!",
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete file type: ' . $e->getMessage(),
            ], 500);
        }
    }
}
