<?php

use App\Http\Controllers\API\ApiKeyController;
use App\Http\Controllers\API\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;

use App\Models\User;
use App\Http\Controllers\API\LicenseController;
use App\Http\Controllers\API\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['middleware' => ['log.route.api']], function (){
    Route::get('/', [AuthController::class, 'home'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    
    Route::middleware('auth:sanctum')->group(function(){
        Route::post('/logout', [AuthController::class, 'logout']);
        
        Route::get('/home', function(){
            if (Auth::check()) {
                return 'berhasil login';
            }else {
                return 'anda belum login';
            }
        });
        
        Route::get('/list-user', [UserController::class, 'list']);
    });
    
    Route::prefix('/user')->group(function(){
        Route::post('/store', [UserController::class, 'store'])->name('user.store');
    });
    Route::get('/get-api-key', [ApiKeyController::class, 'index'])->name('get-api-key');
});