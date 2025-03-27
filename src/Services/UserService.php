<?php
namespace App\Services;

use App\Entity\Users;
use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Error;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserService {
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getUsers(UsersRepository $usersRepository)
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
        return $data;
    }


    public function createUser($data): bool | Error
    {
        $newUser = new Users();
        $newUser->setName($data['name']);
        $newUser->setLastName($data['last_name']);
       
       
      
        $user = $this->entityManager->getRepository(Users::class)->findOneBy(['email' => $data['email']]);
        if ($user) {            
            return new Error('Email already exists');
        }
        $user = $this->entityManager->getRepository(Users::class)->findOneBy(['document' => $data['document']]);
        if ($user) {
            return new Error('Document already exists');
        }
        $newUser->setEmail($data['email']);
        $newUser->setDocument($data['document']);
        $newUser->setPassword(password_hash($data['password'], PASSWORD_DEFAULT));
       try {
        $this->entityManager->persist($newUser);
        $this->entityManager->flush();
        return true;

       } catch (\Throwable $th) {
              return new Error('Error creating user');
       }
    }


    public function login($data): bool | Error
    {
        $user = $this->entityManager->getRepository(Users::class)->findOneBy(['email' => $data['email']]);
        if (!$user) {
            return new Error('Invalid Credentials');
        }
        if (!password_verify($data['password'], $user->getPassword())) {
            return new Error('Invalid Credentials');
        }
        return true;
    }
    
}