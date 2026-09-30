<?php

namespace App\Contracts;

Interface UserRepositoryInterface
{
    /**
     * Create a new class instance.
     */
        public function create(array $data): User;

        public function findById(int $id): ?User;

        public function findByEmail(string $email): ?User;

        public function paginate(int $perPage = 15): LengthAwarePaginator;

        public function update(int $id, array $data): bool;

        public function delete(int $id): bool;
    
}
