<?php

namespace App\Services;

use App\Entity\Client;
use Doctrine\ORM\EntityManagerInterface;
use Error;

class ClientService
{

    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    public function createClient($data): Client | Error | null
    {
        try {

            $client = new Client();
            $client->setName($data['name']);
            $client->setLastName($data['last_name']);
            /* verify email duplicated */
           
            $client->setEmail($data['email']);
            $client->setCountry($data['country']);
            $client->setCity($data['city']);
            $client->setAddress($data['address']);
            $client->setPhone($data['phone']);
            $client->setState($data['state']);
            $this->entityManager->persist($client);
            $this->entityManager->flush();         

            /* return client save */
            
            return $client;
        } catch (\Exception $e) {
            return new Error($e->getMessage());
        }
    }

    public function updateClient($data, $id)
    {
        try {
            $client = $this->entityManager->getRepository(Client::class)->find($id);
            if (!$client) {
                return new Error('Client not found');
            }

            $client->setName($data['name']);
            $client->setLastName($data['last_name']);
            $client->setEmail($data['email']);
            $client->setCountry($data['country']);
            $client->setCity($data['city']);
            $client->setAddress($data['address']);
            $client->setPhone($data['phone']);
            $client->setState($data['state']);

            $this->entityManager->flush();

            return $client;
        } catch (\Exception $e) {
            return new Error($e->getMessage());
        }
    }

    public function deleteClient($id)
    {
        try {
            $client = $this->entityManager->getRepository(Client::class)->find($id);
            if (!$client) {
                return new Error('Client not found');
            }

            $this->entityManager->remove($client);
            $this->entityManager->flush();

            return $client;
        } catch (\Exception $e) {
            return new Error($e->getMessage());
        }
    }

    public function getClient($id)
    {
        try {
            $client = $this->entityManager->getRepository(Client::class)->find($id);
            if (!$client) {
                return new Error('Client not found');
            }

            return $client;
        } catch (\Exception $e) {
            return new Error($e->getMessage());
        }
    }

    public function getClients()
    {
        try {
            return $this->entityManager->getRepository(Client::class)->findAll();
        } catch (\Exception $e) {
            return new Error($e->getMessage());
        }
    }
}