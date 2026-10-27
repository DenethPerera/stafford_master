<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\PrivilegeService;
use Symfony\Component\HttpFoundation\Response;

class CheckPrivilege
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  int  $menuId  Menu ID to check access
     * @param  string|null  $action  Specific action to check (add, edit, statuschange, remove)
     */
    public function handle(Request $request, Closure $next, $menuId, $action = null): Response
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Please login to continue.');
        }

        // Check if action is specified
        if ($action) {
            // Check specific privilege action
            if (!PrivilegeService::check($menuId, $action)) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'You do not have permission to perform this action.'
                    ], 403);
                }
                
                return redirect()->back()->with('error', 'You do not have permission to perform this action.');
            }
        } else {
            // Just check menu access
            if (!PrivilegeService::canAccess($menuId)) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'You do not have permission to access this page.'
                    ], 403);
                }
                
                return redirect()->back()->with('error', 'You do not have permission to access this page.');
            }
        }

        return $next($request);
    }
}
