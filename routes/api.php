<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


      Route::group(['v1'], function() {
        //...All User...//

       
        

         //....auth....//
        Route::group(['prefix'=> 'auth'], function() {
            Route::post('register', [RegisterController::class, 'register']);
            Route::post('login', [LoginController::class, 'login']);
            Route::post('forgot-password', [ForgotPasswordController::class, 'forgotPassword']);
         Route::group(['middleware' => 'auth:sanctum'], function() {
            Route::post('logout', [LogoutController::class, 'logout']);
            Route::post('/email/verification-notification', [VerifyEmailController::class, 'resendNotification'])->name('verification.send');
            Route::post('reset-password', [ResetPasswordController::class, 'resetPassword']); 
 
         });
     });


     Route::group(['prefix' => 'me', 'middleware' => 'auth:sanctum'], function() {
 
        Route::post('/profiles', [ProfileController::class, 'updateProfile']);
        Route::post('/change-password', [ProfileController::class, 'changePassword']);
       });


       Route::group(['middleware' => ['auth:sanctum']], function() {
        Route::group(['middleware' => ['role:super-admin'], 'prefix' => 'admin'], function() {
        Route::get('users', [UserController::class, 'index']);
        Route::post('users', [UserController::class, 'store']);
        Route::get('users/{id}', [UserController::class, 'show']);
        Route::put('users/{id}', [UserController::class, 'update']);
        Route::delete('users/{id}', [UserController::class, 'destroy']);
        Route::post('users/{id}/suspend', [UserController::class, 'suspend']);
        Route::post('users/{id}/active', [UserController::class, 'active']);
        Route::get('users/{id}/roles', [AdminAdminRoleController::class, 'show']);
        Route::get('users/{id}/permissions', [AdminPermissionController::class, 'show']);
        Route::post('users/{id}/roles', [AdminAdminRoleController::class, 'changeRole']);
        Route::post('/products/categories', [CategoryController::class, 'store']);
        Route::put('/products/categories/{id}', [CategoryController::class, 'update']);
        Route::delete('/products/categories/{id}', [CategoryController::class, 'destroy']);
      
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

  });
