<?php

namespace App\Auth\Register\Domain;



interface UserRepositoryInterface
{
    public function save(User $user): void;

    public function findbyEmail(string $email): ?User;
}