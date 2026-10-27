<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\User;
use App\Models\UserPrivilege;
use App\Models\Usertype;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function edit($id)
    {
        if (!checkPrivilege(3, 'edit')) {
            return response()->json(['error' => 'Forbidden'], 403);
        }
        $user = User::findOrFail($id);
        return response()->json($user);
    }
    public function index()
    {
        $user_type = Usertype::where('status', 1)->get(['idtbl_user_type', 'type']);
        return view('users.account', compact('user_type'));



    }
    public function getUsersData()
    {
        $users = User::with('userType')->where('status', '!=', 3)->orderBy('name')->get(['id', 'name', 'username', 'status', 'tbl_user_type_idtbl_user_type']);
        return datatables()->of($users)
            ->addIndexColumn()
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'username' => ['required', 'string', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'user_type' => ['required', 'exists:tbl_user_type,idtbl_user_type'],
            'image' => ['nullable', 'image', 'max:1024'],
        ]);


        try {
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('users/images', 'public');
            }

            $newUser = User::create([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'tbl_user_type_idtbl_user_type' => $request->user_type,
                'imagepath' => $imagePath,
                'status' => 1,
                'updatedatetime' => now(),
            ]);

            $type = Usertype::find($request->user_type);
            if ($type) {
                $roleName = strtolower(str_replace(' ', '-', trim($type->type)));
                $newUser->syncRoles([$roleName]);
            }

            return redirect()->back()->with('success', 'User created successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'User creation failed: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        if (!checkPrivilege(3, 'edit')) {
            return response()->json(['error' => 'Forbidden'], 403);
        }
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,' . $id . ',id'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username,' . $id . ',id'],
            'user_type' => ['required', 'exists:tbl_user_type,idtbl_user_type'],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'image' => ['nullable', 'image', 'max:1024'],
        ]);

        try {
            $user = User::findOrFail($id);

            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('users/images', 'public');
                $user->imagepath = $imagePath;
            }

            $user->name = $request->name;
            $user->username = $request->username;
            $user->tbl_user_type_idtbl_user_type = $request->user_type;
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }
            $user->updatedatetime = now();

            $user->save();

            $type = Usertype::find($request->user_type);
            if ($type) {
                $roleName = strtolower(str_replace(' ', '-', trim($type->type)));
                $user->syncRoles([$roleName]);
            }

            return redirect()->back()->with('success', 'User updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'User update failed: ' . $e->getMessage());
        }


    }
    public function destroy($id)
    {
        if (!checkPrivilege(3, 'remove')) {
            return response()->json(['status' => false, 'message' => 'Forbidden'], 403);
        }
        try {
            $user = User::findOrFail($id);
            $user->status = 3; // Soft delete
            $user->save();
            return response()->json(['status' => true, 'message' => 'User deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Failed to delete user'], 500);
        }
    }

    public function updateStatus(Request $request, $id)
    {
        if (!checkPrivilege(3, 'statuschange')) {
            return response()->json(['status' => false, 'message' => 'Forbidden'], 403);
        }
        try {
            $user = User::findOrFail($id);
            $user->status = $request->status;
            $user->save();

            return response()->json([
                'status' => true,
                'message' => $request->status == 1 ? 'User activated' : ($request->status == 2 ? 'User deactivated' : 'User deleted')
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Action failed'], 500);
        }
    }



    public function typeIndex()
    {
        return view('users.type');
    }

    public function getUsertypeData()
    {
        $usertypes = Usertype::where('status', '!=', 3)->orderBy('type')->get(['idtbl_user_type', 'type', 'status']);
        return datatables()->of($usertypes)
            ->addIndexColumn()
            ->make(true);
    }
    public function addUserType(Request $request)
    {
        validator($request->all(), [
            'type' => ['required', 'string', 'max:100'],
        ])->validate();

        try {
            $typeName = strtoupper(trim($request->type));
            Usertype::create([
                'type' => $typeName,
                'status' => 1,
                'updatedatetime' => now(),
            ]);

            // Sync with Spatie Roles
            $roleSlug = strtolower(str_replace(' ', '-', $typeName));
            Role::findOrCreate($roleSlug);

            return redirect()->back()->with('success', 'User type added successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to add user type: ' . $e->getMessage());
        }
    }

    public function editUserType(Request $request, $id)
    {
        if (!checkPrivilege(2, 'edit')) {
            return response()->json(['error' => 'Forbidden'], 403);
        }
        $userType = Usertype::findOrFail($id);
        return response()->json($userType);
    }

    public function updateUserType(Request $request, $id)
    {
        if (!checkPrivilege(2, 'edit')) {
            return response()->json(['error' => 'Forbidden'], 403);
        }
        $request->validate([
            'type' => ['required', 'string', 'max:100'],
        ]);
        try {
            $userType = Usertype::findOrFail($id);
            $typeName = strtoupper(trim($request->type));
            $userType->type = $typeName;
            $userType->updatedatetime = now();
            $userType->save();

            // Sync with Spatie Roles
            $roleSlug = strtolower(str_replace(' ', '-', $typeName));
            Role::findOrCreate($roleSlug);

            return redirect()->back()->with('success', 'User type updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update user type: ' . $e->getMessage());
        }
    }

    public function updateUserTypeStatus($id)
    {
        if (!checkPrivilege(2, 'statuschange')) {
            return response()->json(['status' => false, 'message' => 'Forbidden'], 403);
        }
        try {
            $userType = Usertype::findOrFail($id);
            $userType->status = $userType->status == 1 ? 2 : 1; // Toggle between active(1) and inactive(2)
            $userType->save();
            return response()->json(['status' => true, 'message' => 'User type status updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Failed to update user type status'], 500);
        }
    }

    public function destroyUserType($id)
    {
        if (!checkPrivilege(2, 'remove')) {
            return response()->json(['status' => false, 'message' => 'Forbidden'], 403);
        }
        if ($id == 1) {
            return response()->json(['status' => false, 'message' => 'SUPER ADMIN user type cannot be deleted.'], 403);
        }
        try {
            $userType = Usertype::findOrFail($id);
            $userType->status = 3; // Soft delete
            $userType->save();
            return response()->json(['status' => true, 'message' => 'User type deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Failed to delete user type'], 500);
        }
    }



    public function privilegeIndex()
    {
        $users = User::where('status', 1)->get(['id', 'name', 'username']);
        $menus = Menu::where('status', 1)->get(['idtbl_menu_list', 'menu']);
        return view('users.privileges', compact('users', 'menus'));
    }

    public function getPrivilegeData()
    {
        $privilege = UserPrivilege::with('menu', 'user:id,name')
            ->whereRelation('user', 'status', 1)
            ->get();

        return datatables()->of($privilege)
            ->addIndexColumn()
            ->make(true);

    }

    public function privilegeAdd(Request $request)
    {
        if (!checkPrivilege(1, 'add')) {
            return response()->json(['status' => false, 'message' => 'Forbidden'], 403);
        }
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'menu_id' => 'required|array',
            'menu_id.*' => 'exists:tbl_menu_list,idtbl_menu_list',
            'access_status' => 'required|boolean',
            'add' => 'required|boolean',
            'edit' => 'required|boolean',
            'statuschange' => 'required|boolean',
            'remove' => 'required|boolean',
        ]);

        try {
            $createdCount = 0;
            $updatedCount = 0;

            foreach ($request->menu_id as $menuId) {
                $privilege = UserPrivilege::where('tbl_user_idtbl_user', $request->user_id)
                    ->where('tbl_menu_list_idtbl_menu_list', $menuId)
                    ->first();

                if ($privilege) {
                    // Update existing privilege
                    $privilege->update([
                        'access_status' => $request->access_status,
                        'add' => $request->add,
                        'edit' => $request->edit,
                        'statuschange' => $request->statuschange,
                        'remove' => $request->remove,
                        'status' => 1,
                        'updatedatetime' => now(),
                    ]);
                    $updatedCount++;
                } else {
                    // Create new privilege
                    UserPrivilege::create([
                        'tbl_user_idtbl_user' => $request->user_id,
                        'tbl_menu_list_idtbl_menu_list' => $menuId,
                        'access_status' => $request->access_status,
                        'add' => $request->add,
                        'edit' => $request->edit,
                        'statuschange' => $request->statuschange,
                        'remove' => $request->remove,
                        'status' => 1,
                        'approvestatus' => 0,
                        'checkstatus' => 0,
                        'updatedatetime' => now(),
                    ]);
                    $createdCount++;
                }
            }

            $message = "Privileges processed successfully";
            if ($createdCount > 0 && $updatedCount > 0) {
                $message = "{$createdCount} privilege(s) created and {$updatedCount} updated";
            } elseif ($createdCount > 0) {
                $message = "{$createdCount} privilege(s) created";
            } elseif ($updatedCount > 0) {
                $message = "{$updatedCount} privilege(s) updated";
            }
            
            // Clear user privilege caches
            Cache::forget("user_privileges_{$request->user_id}");
            Cache::forget("user_full_privileges_{$request->user_id}");

            return response()->json(['status' => true, 'message' => $message]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Failed to assign privileges: ' . $e->getMessage()], 500);
        }
    }

    // Edit privilege for a user and menu
    public function editPrivilege(Request $request, $id)
    {
        // $id is the idtbl_user_privilege
        $privilege = UserPrivilege::findOrFail($id);
        if (!checkPrivilege(1, 'edit')) {
            return response()->json(['status' => false, 'message' => 'Forbidden'], 403);
        }
        $request->validate([
            'access_status' => 'required|boolean',
            'add' => 'required|boolean',
            'edit' => 'required|boolean',
            'statuschange' => 'required|boolean',
            'remove' => 'required|boolean',
            'approvestatus' => 'nullable|integer',
        ]);
        try {
            $privilege->access_status = $request->access_status;
            $privilege->add = $request->add;
            $privilege->edit = $request->edit;
            $privilege->statuschange = $request->statuschange;
            $privilege->remove = $request->remove;
            if ($request->has('approvestatus')) {
                $privilege->approvestatus = $request->approvestatus;
            }
            $privilege->updatedatetime = now();
            $privilege->save();
            
            // Clear user privilege caches
            $userId = $privilege->tbl_user_idtbl_user;
            Cache::forget("user_privileges_{$userId}");
            Cache::forget("user_full_privileges_{$userId}");
            
            return response()->json(['status' => true, 'message' => 'Privilege updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Failed to update privilege: ' . $e->getMessage()], 500);
        }
    }

    public function getPrivilege(Request $request, $id)
    {
        $privilege = UserPrivilege::findOrFail($id);
        return response()->json($privilege);
    }

    public function updatePrivilegeStatus($id)
    {
        $privilege = UserPrivilege::find($id);
        if (!$privilege) {
            return response()->json(['status' => false, 'message' => 'Privilege not found'], 404);
        }

        $temp = $privilege->tbl_user_idtbl_user;
        if (!checkPrivilege(1, 'statuschange')) {
            return response()->json(['status' => false, 'message' => 'Forbidden'], 403);
        }

        try {
            $privilege->status = $privilege->status == 1 ? 2 : 1; // Toggle between active(1) and inactive(2)
            $privilege->save();
            
            // Clear user privilege caches
            $userId = $privilege->tbl_user_idtbl_user;
            Cache::forget("user_privileges_{$userId}");
            Cache::forget("user_full_privileges_{$userId}");
            
            return response()->json(['status' => true, 'message' => 'Privilege status updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Failed to update privilege status'], 500);
        }
    }

    public function deletePrivilege($id)
    {
        $privilege = UserPrivilege::find($id);
        if (!$privilege) {
            return response()->json(['status' => false, 'message' => 'Privilege not found'], 404);
        }

        $temp = $privilege->tbl_user_idtbl_user;
        if (!checkPrivilege(1, 'remove')) {
            return response()->json(['status' => false, 'message' => 'Forbidden'], 403);
        }

        try {
            $userId = $privilege->tbl_user_idtbl_user;
            $privilege->delete();
            
            // Clear user privilege caches
            Cache::forget("user_privileges_{$userId}");
            Cache::forget("user_full_privileges_{$userId}");
            
            return response()->json(['status' => true, 'message' => 'Privilege deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Failed to delete privilege'], 500);
        }
    }

}
