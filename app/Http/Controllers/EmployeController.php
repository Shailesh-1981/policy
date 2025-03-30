<?php

namespace App\Http\Controllers;

use App\Models\RoleModel;
use App\Models\User;
use App\Models\UserRoleModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;


class EmployeController extends Controller
{
    /**
     * Display a listing of the resource.
     */


    public function index()
    {
        $id = auth()->id();
        $perPage = 5; // Number of records per page
        $skip = 0; // Default skip value

        $users = User::with('userRole')
            ->where('id', '!=', $id)
            ->orderBy('id', 'DESC');

        $total = $users->count(); // Get total records count
        $userData = $users->skip($skip)->take($perPage)->get(); // Apply pagination

        return view('Admin.pages.employe.index', [
            'user' => $userData,
            'count' => (int)ceil($total / $perPage) // Total pages count
        ]);
    }


    public function add()
    {
        return view('Admin.pages.employe.add');
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        DB::beginTransaction(); // 🔹 Start transaction

        try {
            $user = User::create([
                'name' => $request->user_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            UserRoleModel::create([
                'user_id' => $user->id,
                'role_id' => $request->user_type,
            ]);

            DB::commit(); // 🔹 Commit transaction if everything is successful

            return response()->json([
                'status' => 200,
                'message' => 'User created successfully',
                'user' => $user
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack(); // 🔹 Rollback transaction on failure

            return response()->json([
                'status' => 500,
                'message' => 'Something went wrong!',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = user::with('userRole')->find($id);
        return view('Admin.pages.employe.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */


    public function updateUser(Request $request)
    {
        DB::beginTransaction();

        try {
            $user = User::find($request->user_id);

            if (!$user) {
                return response()->json([
                    'status' => 404,
                    'message' => 'User not found'
                ], 404);
            }

            $user->update([
                'name' => $request->user_name,
                'email' => $request->email,
                'password' => $request->password ? Hash::make($request->password) : $user->password, // Keep old password if not changed
            ]);

            UserRoleModel::updateOrCreate(
                ['user_id' => $request->user_id],
                ['role_id' => $request->user_type]
            );

            DB::commit();

            return response()->json([
                'status' => 200,
                'message' => 'User updated successfully',
                'user' => $user
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 500,
                'message' => 'Something went wrong!',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        DB::beginTransaction();

        try {

            $user = User::find($id);

            if (!$user) {
                return response()->json([
                    'status' => 404,
                    'message' => 'User not found'
                ], 404);
            }

            UserRoleModel::where('user_id', $id)->delete();

            $user->delete();

            DB::commit();

            return response()->json([
                'status' => 200,
                'message' => 'User deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 500,
                'message' => 'Something went wrong!',
                'error' => $e->getMessage()
            ], 500);
        }
    }

 

    public function search(Request $request)
    {
        $query = $request->input('query');
        $perPage = $request->input('per_page', 10); // Default 10 per page
        $page = $request->input('page', 1);

        $employees = User::with('userRole');

        if ($query) {
            $employees->where('name', 'LIKE', "%{$query}%")
                ->orWhere('email', 'LIKE', "%{$query}%");
        }

        $employees = $employees->paginate($perPage, ['*'], 'page', $page);

        return response()->json($employees);
    }

    public function view($id)
    {
        $data = user::with('userRole')->find($id);
        if (!$data) {
            return response()->json(['message' => 'Policy not found'], 404);
        }
        return response()->json([
            'status'  => 200,
            'message' => 'Employe data successfully',
            'data'  => $data
        ]);
    }

    public function searchPagination(Request $request)
    {
        $query = $request->input('query');
        $skip = $request->input('skip', 0);
        $limit = $request->input('limit', 10); // Default 10 per page

        $employees = User::with('userRole')
            ->when($query, function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                    ->orWhere('email', 'LIKE', "%{$query}%");
            });

        $total = $employees->count(); // Get total count
        $data = $employees->skip($skip)->take($limit)->get(); // Apply pagination manually

        return response()->json([
            'data' => $data,
            'count' => (int)ceil($total / $limit),
        ]);
    }
}
