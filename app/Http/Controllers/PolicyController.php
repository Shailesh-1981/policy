<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StorePolicyRequest;
use App\Http\Requests\UpdatePolicyRequest;
use App\Models\PolicyModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class PolicyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $data = PolicyModel::select();
        return view('Admin.pages.policy.index', ['data' => $data->take(5)->skip(0)->orderBy('id', 'DESC')->get(), 'count' => (int)ceil($data->count() / 5)]);
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(StorePolicyRequest $request): JsonResponse
{
    try {
        $policy = PolicyModel::create([
            'user_id'          => $request->user_id ?? Auth::id(),
            'policy_number'    => $request->policy_number,
            'type'             => $request->policy_type,
            'premium_amount'   => $request->premium_amount,
            'coverage_details' => $request->coverage_details,
            'start_date'       => $request->start_date,
            'end_date'         => $request->end_date,
            'status'           => $request->status,
        ]);

        return response()->json([
            'status'  => 200,
            'message' => 'Policy created successfully',
            'policy'  => $policy
        ]);

    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'status'  => 422,
            'message' => 'Validation Error',
            'errors'  => $e->errors() // Returns an array of validation error messages
        ], 422);
    } catch (\Exception $e) {
        return response()->json([
            'status'  => 500,
            'message' => 'Something went wrong.',
            'error'   => $e->getMessage()
        ], 500);
    }
}


    public function add()
    {
        return view('Admin.pages.policy.add');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $data =  PolicyModel::find($id);
        return view('Admin.pages.policy.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePolicyRequest $request): JsonResponse
    {

        $policy = PolicyModel::find($request->form_id);
        $policy->update([
            'user_id'          => $request->user_id ?? Auth::id(),
            'policy_number'    => $request->policy_number,
            'type'             => $request->policy_type,
            'premium_amount'   => $request->premium_amount,
            'coverage_details' => $request->coverage_details,
            'start_date'       => $request->start_date,
            'end_date'         => $request->end_date,
            'status'           => $request->status,
        ]);

        return response()->json([
            'status'  => 200,
            'message' => 'Policy update successfully',
            'policy'  => $policy
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {

        $policy = PolicyModel::find($id);

        if (!$policy) {
            return response()->json(['message' => 'Policy not found'], 404);
        }
        $policy->delete();
        return response()->json(['message' => 'Policy deleted successfully']);
    }

    public function view($id)
    {
        $policy = PolicyModel::find($id);
        if (!$policy) {
            return response()->json(['message' => 'Policy not found'], 404);
        }
        return response()->json([
            'status'  => 200,
            'message' => 'Policy data successfully',
            'data'  => $policy
        ]);
    }

    public function search(Request $request)
    {
        $query = $request->input('query');

        if (!$query) {
            return response()->json(['data' => PolicyModel::all()]);
        }

        $policies = PolicyModel::select()
            ->when($query != null, function ($q) use ($query) {
                $q->where('policy_number', 'LIKE', "%{$query}%");
                $q->orWhere('premium_amount', 'LIKE', "%{$query}%");
                $q->orWhere('start_date', 'LIKE', "%{$query}%");
                $q->orWhere('status', 'LIKE', "%{$query}%");
            });

        return response()->json(['data' => $policies->take(5)->skip($request->skip)->get(), 'count' => (int)ceil($policies->count() / 5)]);
    }
    public function searchPagination(Request $request)
    {
        $query = $request->input('query');
        $skip = $request->input('skip', 0);

        $policies = PolicyModel::select()
            ->when($query != null, function ($q) use ($query) {
                $q->where('policy_number', 'LIKE', "%{$query}%")
                    ->orWhere('premium_amount', 'LIKE', "%{$query}%")
                    ->orWhere('start_date', 'LIKE', "%{$query}%")
                    ->orWhere('status', 'LIKE', "%{$query}%");
            });

        $total = $policies->count();
        $data = $policies->skip($skip)->take(5)->get();

        return response()->json([
            'data' => $data,
            'count' => (int)ceil($total / 5),
        ]);
    }
}
