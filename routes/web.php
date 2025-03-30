<?php

use App\Http\Controllers\EmployeController;
use App\Http\Controllers\PolicyController;
use App\Http\Controllers\ProfileController;
use App\Models\PolicyModel;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $policyCount = PolicyModel::count();

    $user = User::with('userRole',function($query){
        $query->where('role_id',4);
    })->count();

    $employe = User::whereHas('userRole', function ($query) {
        $query->whereIn('role_id', [2, 3]);
    })->count();


    return view('Admin/pages/dashboard' ,compact('policyCount','user','employe'));
})->middleware(['auth', 'verified'])->name('dashboard');

// route::get('/policy',[PolicyController::class,'index'])->middleware(['auth','verified'])->name('policy-index');
// route::get('/employe',[EmployeController::class,'index'])->middleware(['auth','verified'])->name('employe-index');
// route::get('/profile',[ProfileController::class,'index'])->middleware(['auth','verified'])->name('add-policy');

Route::middleware(['auth', 'verified'])->group(function () {
    //policyController
    Route::get('/policy', [PolicyController::class, 'index'])->name('policy-index');
    Route::get('/add/policy', [PolicyController::class, 'add'])->name('add-policy');
    Route::get('/show-policy/{id}', [PolicyController::class, 'show'])->name('policy.show');





    //employeController
    Route::get('/employe', [EmployeController::class, 'index'])->name('employe-index');
    Route::get('/add/employe', [EmployeController::class, 'add'])->name('add-employe');
    Route::get('/show-employe/{id}', [EmployeController::class, 'show'])->name('employe-show');


    Route::get('/profile', [ProfileController::class, 'index'])->name('add-policy');
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
