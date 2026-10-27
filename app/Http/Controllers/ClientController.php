<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class ClientController extends Controller
{
    /**
     * Display a listing of clients.
     */
    public function index(Request $request): View|JsonResponse
    {
        Gate::authorize('view-clients');

        if ($request->ajax() || $request->has('draw')) {
            $clients = Client::query()->withCount('styles');

            if ($request->filled('status_filter')) {
                $clients->where('status', $request->status_filter);
            }

            return DataTables::eloquent($clients)
                ->addColumn('job_orders_count', fn($row) => $row->styles_count)
                ->addColumn('styles_count', fn($row) => $row->styles_count)
                ->addColumn('status_badge', fn($row) => statusBadge($row->status))
                ->rawColumns(['status_badge'])
                ->make(true);
        }

        return view('clients.index');
    }

    /**
     * Store a newly created client.
     */
    public function store(Request $request): JsonResponse
    {
        Gate::authorize('create-clients');

        $validated = $request->validate([
            'name'    => 'required|string|max:255|unique:clients,name',
            'code'    => 'nullable|string|max:50',
            'phone'   => 'nullable|string|max:255',
            'email'   => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'contact_person' => 'nullable|string|max:255',
            'status'  => 'required|in:Active,Inactive',
            'notes'   => 'nullable|string',
        ]);

        try {
            $client = Client::create($validated);

            activity()
                ->causedBy(auth()->user())
                ->performedOn($client)
                ->withProperties(['name' => $client->name])
                ->log('Client Created');

            return response()->json([
                'success' => true,
                'message' => "Client {$client->name} created successfully!",
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create client: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Return a single client record for editing.
     */
    public function show(Client $client): JsonResponse
    {
        Gate::authorize('view-clients');

        return response()->json($client);
    }

    /**
     * Update the specified client.
     */
    public function update(Request $request, Client $client): JsonResponse
    {
        Gate::authorize('edit-clients');

        $validated = $request->validate([
            'name'    => 'required|string|max:255|unique:clients,name,' . $client->id,
            'code'    => 'nullable|string|max:50',
            'phone'   => 'nullable|string|max:255',
            'email'   => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'contact_person' => 'nullable|string|max:255',
            'status'  => 'required|in:Active,Inactive',
            'notes'   => 'nullable|string',
        ]);

        try {
            $client->update($validated);

            activity()
                ->causedBy(auth()->user())
                ->performedOn($client)
                ->withProperties(['name' => $client->name, 'changes' => $client->getChanges()])
                ->log('Client Updated');

            return response()->json([
                'success' => true,
                'message' => 'Client updated successfully!',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update client: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Toggle the active/inactive status of a client for archiving.
     */
    public function toggleStatus(Client $client): JsonResponse
    {
        Gate::authorize('edit-clients');

        try {
            $newStatus = ($client->status === 'Active' || $client->status === \App\Enums\OrderStatus::ACTIVE) ? 'Inactive' : 'Active';
            $client->update(['status' => $newStatus]);

            activity()
                ->causedBy(auth()->user())
                ->performedOn($client)
                ->withProperties(['name' => $client->name, 'new_status' => $newStatus])
                ->log("Client status updated to {$newStatus}");

            return response()->json([
                'success' => true,
                'message' => "Client '{$client->name}' status changed to {$newStatus} successfully!",
                'status'  => $newStatus,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to toggle client status: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete the specified client.
     */
    public function destroy(Client $client): JsonResponse
    {
        Gate::authorize('delete-clients');

        try {
            if ($client->hasDownstreamDependencies()) {
                return response()->json([
                    'success' => false,
                    'message' => "Cannot delete Client '{$client->name}' because associated PLM Styles exist. Please set status to Inactive instead to preserve historical records.",
                ], 422);
            }

            $name = $client->name;
            $client->delete();

            activity()
                ->causedBy(auth()->user())
                ->withProperties(['name' => $name])
                ->log('Client Soft-Deleted');

            return response()->json([
                'success' => true,
                'message' => "Client '{$name}' deleted successfully!",
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete client: ' . $e->getMessage(),
            ], 500);
        }
    }
}
