<?php

namespace App\Controller;

use App\Entity\Users;
use App\Services\JwtAuthToken;
use App\Repository\UsersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;

final class UserController extends AbstractController
{
    /* entity manager */
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
   
    public function index(UsersRepository $usersRepository): JsonResponse
    {
        $users = $usersRepository->findAll();
        $data = [];

        foreach ($users as $user) {
            $data[] = [
                
                'id' => $user->getId(),
                'name' => $user->getName(),
                'last_name' => $user->getLastName(),
                'document' => $user->getDocument(),
                'email' => $user->getEmail(),
                'password' => $user->getPassword()
                
                
            ];
        }

        return $this->json(['users' => $data]);
    }


    public function create( Request $request, JwtAuthToken $jwtAuthToken ): JsonResponse
    {
       
        $data = json_decode($request->getContent(), true);
       
        $newUser = new Users();
        $newUser->setName($data['name']);
        $newUser->setLastName($data['last_name']);
        /* email is valid */
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return $this->json(['error' => 'Invalid email'], 400);
        }
        /* is unique email */
        $user = $this->entityManager->getRepository(Users::class)->findOneBy(['email' => $data['email']]);
        if ($user) {
            return $this->json(['error' => 'Email already exists'], 400);
        }
        $newUser->setEmail($data['email']);
        /* encript password bcript */

        $newUser->setPassword(password_hash($data['password'], PASSWORD_BCRYPT));
         
        $newUser->setDocument($data['document']);

        try {
            /* persist */
            $this->entityManager->persist($newUser);
            $this->entityManager->flush();

            $jwt = $jwtAuthToken->encode($data['email']);
           
            
            return $this->json([
                'status' => 'User created!',
                'token' => $jwt,
            ], 201);
        } catch (\Throwable $th) {
            return $this->json(['error' => $th->getMessage()], 400);
        }


    }



}
