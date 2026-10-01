<?php

use Illuminate\Http\Request;
use App\Http\Controllers\Api\V1\Auth\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


     Route::prefix('v1')->group(function () {
        //...All User...//

       //....auth....//
        Route::prefix('auth')->group(function () {
            Route::post('/register', [AuthController::class, 'register']);
            Route::post('login', [AuthController::class, 'login']);
            Route::post('forgot-password', [ForgotPasswordController::class, 'forgotPassword']);
         Route::group(['middleware' => 'auth:sanctum', 'verified'], function() {
            Route::post('logout', [AuthController::class, 'logout']);
            Route::get('/auth/email/verify/{id}/{hash}',[AuthController::class, 'verifyEmail'])->middleware(['signed', 'throttle:6,1'])->name('verification.verify');
            Route::post('/auth/email/resend',[AuthController::class, 'resendVerification'])->middleware('throttle:6,1');
            Route::post('reset-password', [ResetPasswordController::class, 'resetPassword']); 
 
         });
     });


     Route::group(['prefix' => 'me', 'middleware' => 'auth:sanctum'], function() {
 
        Route::post('/profiles', [ProfileController::class, 'updateProfile']);
        Route::post('/change-password', [ProfileController::class, 'changePassword']);
       });


    Route::group(['middleware' => ['auth:sanctum']], function() {
      Route::get('/users', [UserController::class, 'index'])
        ->middleware('permission:users.view');

      Route::post('/users', [UserController::class, 'store'])
        ->middleware('permission:users.create');

      Route::get('/users/{user}', [UserController::class, 'show'])
        ->middleware('permission:users.view');

      Route::put('/users/{user}', [UserController::class, 'update'])
        ->middleware('permission:users.update');

      Route::delete('/users/{user}', [UserController::class, 'destroy'])
        ->middleware('permission:users.delete');

        Route::get('users/{id}/roles', [AdminAdminRoleController::class, 'show']);
        Route::get('users/{id}/permissions', [AdminPermissionController::class, 'show']);
        Route::post('users/{id}/roles', [AdminAdminRoleController::class, 'changeRole']);
       
      
       });


       Route::group(
        ['middleware' => ['role:store-owner'], 
          'prefix' => 'owner'], 
           function() {
              Route::post('stores', [StoreController::class, 'store']);
            Route::group(['middleware' => 'isStoreOwner'], function() {
                Route::get('stores', [StoreController::class, 'index']);  
                Route::get('stores/{id}', [StoreController::class, 'show']);
                Route::put('stores/{id}', [StoreController::class, 'update']);
                Route::delete('stores/{id}', [StoreController::class, 'destroy']);
                Route::get('stores/{storeId}/brands', [BrandController::class, 'index']);
                Route::post('stores/{storeId}/brands', [BrandController::class, 'store']);
                Route::get('stores/{storeId}/brands/{id}', [BrandController::class, 'show']);
                Route::put('stores/{storeId}/brands/{id}', [BrandController::class, 'update']);
                Route::delete('stores/{storeId}/brands/{id}', [BrandController::class, 'destroy']);
                Route::get('stores/{storeId}/brands/{brandId}/productlines', [ProductLineController::class, 'index']);
                Route::post('stores/{storeId}/brands/{brandId}/productlines', [ProductLineController::class, 'store']);
                Route::get('stores/{storeId}/brands/{brandId}/productlines/{id}', [ProductLineController::class, 'show']);
                Route::put('stores/{storeId}/brands/{brandId}/productlines/{id}', [ProductLineController::class, 'update']);
                Route::delete('stores/{storeId}/brands/{brandId}/productlines/{id}', [ProductLineController::class, 'destroy']);
                Route::get('stores/{storeId}/productlines/{productLineId}/products', [ProductController::class, 'index']);
                Route::get('stores/{storeId}/productlines/{productLineId}/products/{id}', [ProductController::class, 'show']);
                Route::post('stores/{storeId}/productlines/{productLineId}/products', [ProductController::class, 'store']);
                Route::put('stores/{storeId}/productlines/{productLineId}/products/{id}', [ProductController::class, 'update']);
                Route::delete('stores/{storeId}/productlines/{productLineId}/products/{id}', [ProductController::class, 'destroy']);
            });
        
       });

    });

