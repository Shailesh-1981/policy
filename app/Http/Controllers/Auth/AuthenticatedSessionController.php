<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Tymon\JWTAuth\Facades\JWTAuth;



class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */

     public function store(LoginRequest $request): JsonResponse
     {
         try {
             $credentials = $request->only('email', 'password');

             // ✅ Authenticate user with Auth guard
             if (!Auth::attempt($credentials)) {
                 return response()->json(['error' => 'Unauthorized'], 401);
             }

             // ✅ Generate JWT token for API access
             if (!$token = JWTAuth::attempt($credentials)) {
                 return response()->json(['error' => 'Token generation failed'], 500);
             }

             return response()->json([
                 'status' => 200,
                 'message' => 'Login successful',
                 'token' => $token, // ✅ JWT Token
                 'user' => Auth::user(), // ✅ Authenticated User Data
             ]);
         } catch (\Exception $e) {
             return response()->json([
                 'success' => false,
                 'message' => 'Invalid credentials.',
             ], 422);
         }
     }



    // public function store(LoginRequest $request): JsonResponse
    // {
    //     try {
    //         // if (!$request->expectsJson()) {
    //         //     return response()->json(['success' => false, 'message' => 'Invalid request format.'], 400);
    //         // }
    //         // $request->authenticate();
    //         // dd($request->authenticate());

    //         // $request->session()->regenerate();
    //         // if(Auth::attempt($request->all()))
    //         // {
    //             // $token = $request->user()->createToken('authToken')->plainTextToken;
    //             // if (!$token = Auth::guard('api')->attempt($request->all())) {
    //             //     return response()->json(['error' => 'Unauthorized'], 401);
    //             // }

    //             // // return $this->respondWithToken($token);
    //             // return response()->json([
    //             //     'status' => 200,
    //             //     'message' => 'Login successful',
    //             //     'token' => $token,
    //             //     'user' => $request->user()->only(['id', 'name', 'email']),
    //             // ]);
    //         // }

    //         $credentials = $request->only('email', 'password');

    //         if (!$token = Auth::guard('web')->attempt($credentials)) {
    //             return response()->json(['error' => 'Unauthorized'], 401);
    //         }
    //         dd($token);

    //         return response()->json([
    //             'status' => 200,
    //             'message' => 'Login successful',
    //             'token' => $token,
    //             'user' => Auth::guard('web')->user(),
    //         ]);
    //     } catch (\Exception $e) {
    //         dd($e->getMessage());
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Invalid credentials.',
    //         ], 422);
    //     }
    // }

    // public function store(LoginRequest $request): RedirectResponse
    // {
    //     $request->authenticate();

    //     $request->session()->regenerate();

    //     return redirect()->intended(route('dashboard', absolute: false));
    // }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
