<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Contracts\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    public function create(array $data): User
    {
        // Implementation for creating a new user

        return User::create($data);
    }

    public function findById(int $id): ?User
    {
        // Implementation for finding a user by ID

        return User::find($id);
    }

    public function findByEmail(string $email): ?User
    {
        // Implementation for finding a user by email

        return User::where('email', $email)->first();
    }

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        // Implementation for paginating users

        return User::query()
                    ->latest()
                    ->paginate($perPage);
    }

    public function update(int $id, array $data): bool
    {
        // Implementation for updating a user

       $user->update($data);

        return $user->refresh();
    }

    public function delete(User $user): bool
    {

        // Implementation for deleting a user

        return (bool) $user->delete();
    }
}