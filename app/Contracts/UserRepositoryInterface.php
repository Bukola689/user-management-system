<?php

namespace App\Contracts;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

Interface UserRepositoryInterface
{
    /**
     * Create a new class instance.
     */
       public function create(array $data): User;

       public function findById(int $id): ?User;

       public function findByEmail(string $email): ?User;

       public function paginate(
         int $perPage = 15,
         ?string $search = null,
         ?string $status = null
         ): LengthAwarePaginator;

       public function update(User $user, array $data): User;

       public function delete(User $user): bool;
    
}
