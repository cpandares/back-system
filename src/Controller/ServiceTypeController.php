<?php

namespace App\Controller;

use App\Entity\TypeService;
use App\Enums\TipoServicioAsignar;
use App\Repository\TypeServiceRepository;
use App\Services\JwtAuthToken;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;

final class ServiceTypeController extends AbstractController
{
    private  $jwtToken;
    private $typeServiceRepository;
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager, JwtAuthToken $jwtToken)
    {
        $this->entityManager = $entityManager;
        $this->jwtToken = $jwtToken;
        $this->typeServiceRepository = $entityManager->getRepository(TypeService::class);
    }

    public function index(Request $request, TypeServiceRepository $serviceType): JsonResponse{
        $isAuth = $this->jwtToken->validateToken($request);
        if (!$isAuth) {
            return $this->json(['error' => 'Unauthorized'], 401);
        }

        

        try {
            $data = $serviceType->findAll();
            return $this->json(['serviceTypes' => $data]);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'An error occurred while fetching service types'], 500);
        }

    }

    public function create(Request $request): JsonResponse{
        $isAuth = $this->jwtToken->validateToken($request);
        if (!$isAuth) {
            return $this->json(['error' => 'Unauthorized'], 401);
        }

        $data = json_decode($request->getContent(), true);

        $requiredFields = ['name', 'description'];
        foreach ($requiredFields as $field) {
            if (!isset($data[$field])) {
                return $this->json(['error' => "Missing required field: $field"], 400);
            }
        }

        try {
            $typeService = new TypeService();
            $typeService->setName($data['name']);
            $typeService->setDescription($data['description']);
            $typeService->setAsignTo($data['asignTo'] ?? TipoServicioAsignar::SERVICIO);            
            $typeService->setStatus($data['status'] ?? true);
            

            $this->entityManager->persist($typeService);
            $this->entityManager->flush();

            return new JsonResponse(['message' => 'Service type created successfully'], 201);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'An error occurred while creating the service type'], 500);
        }
    }

}
