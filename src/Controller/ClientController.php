<?php

namespace App\Controller;

use App\Dtos\clients\CreateClientDto;
use App\Entity\Client;
use App\Repository\ClientRepository;
use App\Services\ClientService;
use App\Services\JwtAuthToken;
use Doctrine\ORM\EntityManagerInterface;
use Error;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;

final class ClientController extends AbstractController
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function index(Request $request, ClientRepository $clientRepository, JwtAuthToken $jwtAuthToken): JsonResponse
    {


        $isAuth = $jwtAuthToken->validateToken($request);
        if (!$isAuth) {
            return $this->json(['error' => 'Unauthorized'], 401);
        }
        $clients = $clientRepository->findAll();
        return new JsonResponse($clients);
    }



    public function create(Request $request, JwtAuthToken $jwtAuthToken, ClientService $clientService, SerializerInterface $serializer): JsonResponse
    {

        $isAuth = $jwtAuthToken->validateToken($request);
        if (!$isAuth) {
            return $this->json(['error' => 'Unauthorized'], 401);
        }


        $data = json_decode($request->getContent(), true);

        $requiredFields = ['name', 'last_name', 'email', 'country', 'city', 'address', 'phone', 'state'];
        foreach ($requiredFields as $field) {
            if (!isset($data[$field])) {
                return $this->json(['error' => "Missing required field: $field"], 400);
            }
        }

        $createClientDto = new CreateClientDto($data['name'], $data['last_name'], $data['email'], $data['country'], $data['city'], $data['address'], $data['phone'], $data['state']);
        $client = $createClientDto->registerClientDto();
        if ($client instanceof Error) {
            return $this->json(['error' => $client->getMessage()], 400);
        }

        $client = $this->entityManager->getRepository(Client::class)->findOneBy(['email' => $data['email']]);
        if ($client) {            
            return $this->json(['error' => 'Email already exists'], 400);
        }
       

        $client = $clientService->createClient($data);
      
        if ($client instanceof Error) {
            return $this->json(['error' => $client->getMessage()], 400);
        }


        $jsonClient = $serializer->serialize($client, 'json');

        return new JsonResponse($jsonClient, 200, [], true);
       
    }

    public function update(Request $request, JwtAuthToken $jwtAuthToken, ClientService $clientService, SerializerInterface $serializer, $id): JsonResponse
    {
        $isAuth = $jwtAuthToken->validateToken($request);
        if (!$isAuth) {
            return $this->json(['error' => 'Unauthorized'], 401);
        }

        $data = json_decode($request->getContent(), true);

        $client = $clientService->updateClient($data, $id);
        if ($client instanceof Error) {
            return $this->json(['error' => $client->getMessage()], 400);
        }

        $jsonClient = $serializer->serialize($client, 'json');

        return new JsonResponse($jsonClient, 200, [], true);
    }


}
