<?php 


namespace App\Services;

// use App\Contracts\UserRepositoryInterface;
// use App\DTOs\User\CreateUserDTO;
// use App\Events\UserRegistered;
// use App\Models\User;
// use Illuminate\Support\Facades\DB;
// use Illuminate\Support\Facades\Hash;


Class UserService
{
    public function __construct(
        private readonly UserRepositoryInterface $users
    ) {}

    public function create(CreateUserDTO $dto): User
    {
        return DB::transaction(function () use ($dto) {
            $user = $this->users->create([
                'name' => $dto->name,
                'email' => $dto->email,
                'password' => Hash::make($dto->password),
                'phone' => $dto->phone,
                'status' => 'active',
            ]);

           // event(new UserRegistered($user));

            return $user;
        });
    }

     /*
    |--------------------------------------------------------------------------
    | Login
    |--------------------------------------------------------------------------
    */

    public function login(LoginUserDTO $dto): array
    {
        $user = $this->users->findByEmail($dto->email);

        if (!$user) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        if (!$user->isActive()) {
            throw ValidationException::withMessages([
                'email' => [
                    'This account is not currently active.'
                ],
            ]);
        }

        if (!Hash::check($dto->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $user->update([
            'last_login_at' => now(),
        ]);

        $token = $user->createToken(
            'api-token',
            [
                'users:read',
                'users:update',
                'profile:read',
                'profile:update',
            ]
        )->plainTextToken;

        return [
            'user' => $user->fresh(),
            'token' => $token,
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Logout
    |--------------------------------------------------------------------------
    */

    public function logout(User $user): void
    {
        $user->currentAccessToken()?->delete();
    }

    /*
    |--------------------------------------------------------------------------
    | Update
    |--------------------------------------------------------------------------
    */

    public function update(
        User $user,
        UpdateUserDTO $dto
    ): User {
        return DB::transaction(function () use ($user, $dto) {

            return $this->users->update(
                $user,
                $dto->toArray()
            );
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Delete
    |--------------------------------------------------------------------------
    */

    public function delete(User $user): bool
    {
        return DB::transaction(function () use ($user) {

            $user->tokens()->delete();

            return $this->users->delete($user);
        });
    }

}