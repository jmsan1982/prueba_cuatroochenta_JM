<?php

namespace App\Services;

use App\Auth\Login\Domain\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtAuth
{
    public $manager;
    public string $key;

    public function __construct($manager)
    {
        $this->manager = $manager;
        $this->key = "prueba_cuatroochenta_api_987788454654";
    }


    public function signup(string $email,string $password, $getToken = null)
    {
        $signup = false;

        $user = $this->manager->getRepository(User::class)->findOneBy([
            'email' => $email,
            'password' => $password
        ]);

        if (!is_null($user)) {
            $signup = true;
        }

        if ($signup){

            $token = [
                'sub' => $user->getId(),
                'name' => $user->getName(),
                'surname' => $user->getSurname(),
                'email' => $user->getEmail(),
                'iat' => time(),
                'exp' => time() + (7 * 24 * 60 * 60)
            ];

            $jwt = JWT::encode($token, $this->key, 'HS256');

            if (!empty($getToken)){
                $data = $jwt;
            }else{
                $decoded = JWT::decode($jwt, new Key($this->key, 'HS256'));
                $data = $decoded;
            }
        }else{
            $data = [
                'status' => 'error',
                'message' => 'Token error'
            ];
        }

        return $data;
    }

    public function checkToken($jwt, bool $identity = false)
    {
        $auth = false;

        try {
            $decode = JWT::decode($jwt, new Key($this->key, 'HS256'));
        }catch (\UnexpectedValueException $e){
            $auth = false;
        }catch (\DomainException $e){
             $auth = false;
        }

        if (!empty($decode) && isset($decode) && is_object($decode) && isset($decode->sub)) {
            $auth = true;
        }

        if ($identity != false){
            return $decode;
        }else{
            return $auth;
        }
    }
}