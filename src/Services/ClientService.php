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
       throw new Error('Method not implemented');
    }

    public function deleteClient($id)
    {
        throw new Error('Method not implemented');
    }

    public function getClient($id)
    {
        throw new Error('Method not implemented');
    }

    public function getClients()
    {
        throw new Error('Method not implemented');
    }
}