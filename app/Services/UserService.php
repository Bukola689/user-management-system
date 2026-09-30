<?php 


namespace App\Services;

use App\Contracts\UserRepositoryInterface;
use App\DTOs\User\CreateUserDTO;
use App\Events\UserRegistered;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


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

            event(new UserRegistered($user));

            return $user;
        });
    }
}