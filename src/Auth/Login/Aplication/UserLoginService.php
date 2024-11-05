<?php

namespace App\Auth\Login\Aplication;


use App\Auth\Login\Domain\UserRepositoryInterface;
use App\Services\JwtAuth;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserLoginService
{
    private UserRepositoryInterface $userLoginRepository;
    private JwtAuth $jwtAuth;

    public function __construct(UserRepositoryInterface $userLoginRepository, JwtAuth $jwtAuth)
    {
        $this->userLoginRepository = $userLoginRepository;
        $this->jwtAuth = $jwtAuth;
    }

    public function loginUser(string $email, string $password):JsonResponse
    {
        $user = $this->userLoginRepository->findbyEmail($email);

        if (!$user){
            throw new \InvalidArgumentException("Invalid credentials");
        }

        $pwd = hash('sha256', $password);

        if ($user->getPassword() !== $pwd){
            throw new \InvalidArgumentException("Invalid credentials");
        }

        $jwtAuth = $this->jwtAuth;

        if ($jwtAuth !== null){
            $signup = $jwtAuth->signup($email, $pwd);
        }else{
            $signup = $jwtAuth->signup($email, $pwd);
        }

        return new JsonResponse($signup);
    }
}
