<?php

namespace App\Auth\Login\Domain;

interface UserRepositoryInterface
{
    public function save(User $user): void;

    public function findbyEmail(string $email): ?User;
}