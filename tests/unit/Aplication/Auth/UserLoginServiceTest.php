<?php

namespace App\Tests\Unit\Aplication\Auth;

use App\Auth\Login\Aplication\UserLoginService;
use App\Auth\Login\Domain\UserRepositoryInterface;
use App\Services\JwtAuth;
use App\Auth\Login\Domain\User;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserLoginServiceTest extends TestCase
{
    private $userRepositoryMock;
    private $jwtAuthMock;
    private $userLoginService;

    protected function setUp(): void
    {
        $this->userRepositoryMock = $this->createMock(UserRepositoryInterface::class);
        $this->jwtAuthMock = $this->createMock(JwtAuth::class);
        $this->userLoginService = new UserLoginService($this->userRepositoryMock, $this->jwtAuthMock);
    }

    public function testLoginUserSuccessful()
    {
        $email = 'pruebas@pruebas.com';
        $password = 'password123';
        $hashedPassword = hash('sha256', $password);

        $userMock = $this->createMock(User::class);
        $userMock->method('getPassword')->willReturn($hashedPassword);

        $this->userRepositoryMock
            ->method('findbyEmail')
            ->with($email)
            ->willReturn($userMock);

        $this->jwtAuthMock
            ->method('signup')
            ->with($email, $hashedPassword)
            ->willReturn([
                'status' => 'success',
                'code' => 201,
                'token' => 'jwt_token',
            ]);

        $response = $this->userLoginService->loginUser($email, $password);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('success', $responseData['status']);
        $this->assertEquals(201, $responseData['code']);
        $this->assertEquals('jwt_token', $responseData['token']);
    }

    public function testLoginUserWithInvalidEmail()
    {
        $email = 'novalido@pruebas.com';
        $password = 'password123';

        $this->userRepositoryMock
            ->method('findbyEmail')
            ->with($email)
            ->willReturn(null);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid credentials');

        $this->userLoginService->loginUser($email, $password);
    }

    public function testLoginUserWithInvalidPassword()
    {
        $email = 'test@pruebas.com';
        $password = 'password_invalido';
        $hashedPassword = hash('sha256', 'password123'); //correct password

        $userMock = $this->createMock(User::class);
        $userMock->method('getPassword')->willReturn($hashedPassword);

        $this->userRepositoryMock
            ->method('findbyEmail')
            ->with($email)
            ->willReturn($userMock);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid credentials');

        $this->userLoginService->loginUser($email, $password);
    }
}

