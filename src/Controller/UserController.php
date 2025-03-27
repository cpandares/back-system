<?php

namespace App\Controller;

use App\Dtos\users\RegisterUserDto;
use App\Dtos\users\LoginUserDto;
use App\Entity\Users;
use App\Services\JwtAuthToken;
use App\Repository\UsersRepository;
use App\Services\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Error;

final class UserController extends AbstractController
{
    /* entity manager */
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
   
    public function index(UserService $userSevice): JsonResponse
    {
        $data = $userSevice->getUsers($this->entityManager->getRepository(Users::class));

        return $this->json(['users' => $data]);
    }


    public function create( Request $request, JwtAuthToken $jwtAuthToken, UserService $userSevice ): JsonResponse
    {
       
        $data = json_decode($request->getContent(), true);
        $registerUserDto = new RegisterUserDto(
            $data['name'],
            $data['last_name'],
            $data['email'],
            $data['document'],
            $data['password']
        );
        $validationError = $registerUserDto->registerUserDto();
        if ($validationError) {
            return $this->json(['error' => $validationError->getMessage()], 400);
        }

        $user = $userSevice->createUser($data);

       

        try {
            if( $user instanceof Error){
                return $this->json(['error' => $user->getMessage()], 400);

            }
            $jwt = $jwtAuthToken->encode($data['email']);
            
            return $this->json([
                'status' => 'User created!',
                'token' => $jwt,
            ], 201);
        } catch (\Throwable $th) {
            return $this->json(['error' => $th->getMessage()], 400);
        }


    }

    public function login(Request $request, UserService $userSevice,JwtAuthToken $jwtAuthToken): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $userDto = new LoginUserDto(
            $data['email'],
            $data['password']
        );
        $validationError = $userDto->loginUserDto();
        if ($validationError) {
            return $this->json(['error' => $validationError->getMessage()], 400);
        }

        $user = $userSevice->login($data);
        if ($user instanceof Error) {
            return $this->json(['error' => $user->getMessage()], 400);
        }
        $jwt = $jwtAuthToken->encode($data['email']);
        return $this->json([
            'status' => 'User logged in!',
            'token' => $jwt,
        ], 200);
        
    }



}
