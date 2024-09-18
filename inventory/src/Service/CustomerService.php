<?php

namespace App\Service;

use App\Repository\CustomerRepository;
use Doctrine\ORM\EntityManagerInterface;

class CustomerService
{
    private $entityRepository;
    private $entityManager;

    public function __construct(CustomerRepository $entityRepository, EntityManagerInterface $entityManager)
    {
        $this->entityRepository = $entityRepository;
        $this->entityManager = $entityManager;
    }

    public function findAll()
    {
        return $this->entityRepository->findAll();
    }

    // Define other methods as needed, e.g., findById, create, update, delete
}