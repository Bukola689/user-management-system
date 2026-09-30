<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\DTOs\User\CreateUserDTO;
use App\Http\Requests\Api\V1\Auth\RegisterRequest;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
// use Illuminate\Http\JsonResponse;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(
        private readonly UserService $userService
    ) {}

     /*
    |--------------------------------------------------------------------------
    | Register
    |--------------------------------------------------------------------------
    */

    public function register(
        RegisterRequest $request
    ): JsonResponse {
        $dto = CreateUserDTO::fromArray(
            $request->validated()
        );

        $user = $this->userService->register($dto);

         event(new Registered($user));

        return response()->json([
            'success' => true,
            'message' => 'Account created successfully.',
            'data' => new UserResource($user),
        ], 201);
    }

    /*
    |--------------------------------------------------------------------------
    | Login
    |--------------------------------------------------------------------------
    */

    public function login(
        LoginRequest $request
    ): JsonResponse {
        $dto = LoginUserDTO::fromArray(
            $request->validated()
        );

        $result = $this->userService->login($dto);

        return response()->json([
            'success' => true,
            'message' => 'Login successful.',
            'data' => [
                'user' => new UserResource(
                    $result['user']
                ),

                'token' => $result['token'],

                'token_type' => 'Bearer',
            ],
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Current User
    |--------------------------------------------------------------------------
    */

    public function me(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Authenticated user retrieved successfully.',
            'data' => new UserResource(
                $request->user()
            ),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Logout
    |--------------------------------------------------------------------------
    */

    public function logout(Request $request): JsonResponse
    {
        $this->userService->logout(
            $request->user()
        );

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully.',
            'data' => null,
        ]);
    }

    public function verifyEmail(
    EmailVerificationRequest $request
        ): JsonResponse {
    if ($request->user()->hasVerifiedEmail()) {
        return response()->json([
            'success' => true,
            'message' => 'Email is already verified.',
        ]);
    }

    $request->fulfill();

    return response()->json([
        'success' => true,
        'message' => 'Email verified successfully.',
    ]);
  }

  public function resendVerification(
    Request $request
       ): JsonResponse {
    if ($request->user()->hasVerifiedEmail()) {
        return response()->json([
            'success' => false,
            'message' => 'Email is already verified.',
        ], 422);
    }

    $request->user()->sendEmailVerificationNotification();

    return response()->json([
        'success' => true,
        'message' => 'Verification email sent.',
    ]);
  }
}
