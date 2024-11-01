<?php


namespace App\Auth\Register\Aplication;

use App\Auth\Register\Domain\User;
use App\Auth\Register\Domain\UserRepositoryInterface;

class UserRegistrationService
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository){
        $this->userRepository = $userRepository;
    }

    public function registerUser(string $name, string $surname, string $email, string $password):void
    {

        if ($this->userRepository->findbyEmail($email)){
            throw new \InvalidArgumentException('This email is already registered');
        }

        $pwd = hash('sha256', $password);

        $user = new User($name, $surname, $email, $pwd);

        $this->userRepository->save($user);

    }
}