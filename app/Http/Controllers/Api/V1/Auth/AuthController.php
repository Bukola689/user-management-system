<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\DTOs\User\CreateUserDTO;
use App\Http\Requests\Api\V1\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(
        private readonly UserService $userService
    ) {}

    public function register(RegisterRequest $request)
    {
        $dto = new CreateUserDTO(
            name: $request->string('name')->toString(),
            email: $request->string('email')->toString(),
            password: $request->string('password')->toString(),
            phone: $request->input('phone'),
        );

        $user = $this->userService->create($dto);

        return response()->json([
            'success' => true,
            'message' => 'Account Created Successfully',
            'data' => new UserResource($user),
        ], 201);
    }
}
