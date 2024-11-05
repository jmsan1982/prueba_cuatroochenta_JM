<?php

namespace App\Tests\Unit\Aplication\Auth;

use App\Auth\Register\Aplication\UserRegistrationService;
use App\Auth\Register\Domain\UserRepositoryInterface;
use App\Auth\Register\Domain\User;
use PHPUnit\Framework\TestCase;

class UserRegistrationServiceTest extends TestCase
{
    private $userRepositoryMock;
    private $userRegistrationService;

    protected function setUp(): void
    {
        $this->userRepositoryMock = $this->createMock(UserRepositoryInterface::class);
        $this->userRegistrationService = new UserRegistrationService($this->userRepositoryMock);
    }

    public function testRegisterUserSuccessfully()
    {
        $name = 'jose maria';
        $surname = 'pruebas';
        $email = 'pruebas@pruebas.com';
        $password = 'password123';
        $hashedPassword = hash('sha256', $password);

        $this->userRepositoryMock
            ->method('findbyEmail')
            ->with($email)
            ->willReturn(null);

        $this->userRepositoryMock
            ->expects($this->once())
            ->method('save')
            ->with($this->callback(function ($user) use ($name, $surname, $email, $hashedPassword) {
                return $user instanceof User &&
                    $user->getName() === $name &&
                    $user->getSurname() === $surname &&
                    $user->getEmail() === $email &&
                    $user->getPassword() === $hashedPassword;
            }));

        $this->userRegistrationService->registerUser($name, $surname, $email, $password);
    }

    public function testRegisterUserWithAlreadyRegisteredEmail()
    {
        $name = 'antonio';
        $surname = 'cruz';
        $email = 'antonio@pruebas.com';
        $password = 'password123';

        $userMock = $this->createMock(User::class);

        $this->userRepositoryMock
            ->method('findbyEmail')
            ->with($email)
            ->willReturn($userMock);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('This email is already registered');

        $this->userRegistrationService->registerUser($name, $surname, $email, $password);
    }
}

