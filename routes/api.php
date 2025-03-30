<?php

use App\Http\Controllers\EmployeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PolicyController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/app/login', [LoginController::class, 'login'])->name('api.login');
Route::post('/app/register', [LoginController::class, 'register'])->name('api.login');

// Route::post('/store-policy',[PolicyController::class,'store'])->name('store-policy');
Route::middleware(['jwtCheck'])->group(function () {

    // ***********************************PolicyController*****************************************************
    Route::post('/store-policy', [PolicyController::class, 'store'])->name('policy.store');
    Route::post('/update-policy', [PolicyController::class, 'update'])->name('policy.update');
    Route::post('/destroy-policy/{id}', [PolicyController::class, 'destroy'])->name('policy.destroy');
    Route::get('/view-policies/{id}', [PolicyController::class, 'view'])->name('policy.view');
    Route::get('/search', [PolicyController::class, 'search'])->name('policy.search');
    Route::get('/search/pagination', [PolicyController::class, 'searchPagination'])->name('policy.search.pagination');


    // ***********************************EmployeController*****************************************************
    Route::post('/store-employe', [EmployeController::class, 'store'])->name('employe.store');
    Route::post('/update-user', [EmployeController::class, 'updateUser'])->name('employe.update');
    Route::post('/destroy-employe/{id}', [EmployeController::class, 'destroy'])->name('employe.destroy');
    Route::get('/employe/search', [EmployeController::class, 'search'])->name('employe.search');
    Route::get('/employe/searchPagination', [EmployeController::class, 'searchPagination'])->name('employe.searchPagination');
    Route::get('/view-employe/{id}', [EmployeController::class, 'view'])->name('employe.view');
});
