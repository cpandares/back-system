<?php

namespace App\Services;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManager;
use App\Entity\User;
use App\Entity\Users;

class JwtAuthToken
{
    private  $key;
    private string $alg = 'HS256';

    public function __construct()
    {
        $this->key = $_ENV['JWT_SECRET'];
    }
    public function encode($data)
    {
        $secret = $_ENV['JWT_SECRET'];
        $payload = [
            'email' => $data,
            'exp' => (new \DateTime())->modify('+1 day')->getTimestamp()
        ];
        $jwt = JWT::encode($payload, $secret, $this->alg);
        return $jwt;
    }

    public function decode($jwt)
    {

        try {
            $decoded = JWT::decode($jwt, new Key($this->key, $this->alg));
            return $decoded;
        } catch (\Exception $e) {
            return false;
        }
    }


    public function validateToken($request)
    {

        $token = $request->headers->get('Authorization');
        if (!$token) {
            return false;
        }
        $token = str_replace('Bearer ', '', $token);


        $decoded = $this->decode($token);
        if (!$decoded) {
            return false;
        }
        return true;
    }
}
