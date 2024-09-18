<?php

namespace App\Service;

use App\Entity\Inventory;
use App\Repository\InvetoryRepository;
use Doctrine\ORM\EntityManagerInterface;
class InventoryService
{
    private $entityRepository;
    private $entityManager;

    public function __construct(InvetoryRepository $entityRepository, EntityManagerInterface $entityManager)
    {
        $this->entityRepository = $entityRepository;
        $this->entityManager = $entityManager;
    }

    public function findAll()
    {
        return $this->entityRepository->findAll();
    }

    public function addInventory($inventory)
    {
        $this->entityManager->persist($inventory);
        $this->entityManager->flush();
    }
}
