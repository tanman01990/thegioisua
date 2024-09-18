<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Category;
use App\Service\InventoryService;
use App\Service\ProductService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class InventoryController extends AbstractController
{

    #[Route('/api/inventory', name: 'get_inventory', methods: ['GET'])]
    public function index(InventoryService $invetoryService, Request $request): JsonResponse
    {
        $result = $invetoryService->findAll();
        return $this->json($result);
    }

}
