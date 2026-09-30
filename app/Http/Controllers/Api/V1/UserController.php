<?php

namespace App\Http\Controllers\Api\V1;

use App\DTOs\User\UpdateUserDTO;
use App\Contracts\UserRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\User\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
      public function __construct(
        // private readonly UserService $userService
           private readonly UserRepositoryInterface $users

    ) {
    }

     /*
    |--------------------------------------------------------------------------
    | List users
    |--------------------------------------------------------------------------
    */

    public function index(Request $request): JsonResponse
    {
        $perPage = min(
            $request->integer('per_page', 15),
            100
        );

        $users = $this->users->paginate(
            perPage: $perPage,
            search: $request->input('search'),
            status: $request->input('status')
        );

        return response()->json([
            'success' => true,
            'message' => 'Users retrieved successfully.',
            'data' => UserResource::collection($users),
            'meta' => [
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
                'per_page' => $users->perPage(),
                'total' => $users->total(),
            ],
        ]);
    }

    public function show(User $user): JsonResponse
    {
        $user = $this->users->findById($user);

        abort_if(
            !$user,
            404,
            'User not found.'
        );

        return response()->json([
            'success' => true,
            'message' => 'User retrieved successfully.',
            'data' => new UserResource($user),
        ]);
    }

    public function update(
        UpdateUserRequest $request,
        User $user
    ): JsonResponse {
        $user = $this->users->findById($user);

        abort_if(
            !$user,
            404,
            'User not found.'
        );

        $dto = UpdateUserDTO::fromArray(
            $request->validated()
        );

        $user = $this->userService->update(
            $user,
            $dto
        );

        return response()->json([
            'success' => true,
            'message' => 'User updated successfully.',
            'data' => new UserResource($user),
        ]);
    }

    public function destroy(User $user): JsonResponse
    {
        $user = $this->users->findById($user);

        abort_if(
            !$user,
            404,
            'User not found.'
        );

        $this->userService->delete($user);

        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully.',
            'data' => null,
        ]);
    }
}
