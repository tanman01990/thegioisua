<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Category;
use App\Service\ProductService;
use App\Service\TransactionService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TransactionController extends AbstractController
{
    #[Route('/api/transaction', name: 'get_transaction', methods: ['GET'])]
    public function index(TransactionService $transactionService, Request $request): JsonResponse
    {
        $limit = $request->query->get('limit', 10);
        $page = $request->query->get('page', 1);
        $type = $request->query->get('type', 'Xuat');
        $pagination = $transactionService->findAllWithPagination($type, $limit, $page);
        return $this->json($pagination);
    }

    // get Query By inventory and Transaction Type in Period time but condition can dynamic i mean we can null or not null
    #[Route('/api/transaction/query', name: 'query_transaction', methods: ['GET'])]
    public function query(TransactionService $transactionService, Request $request): JsonResponse
    {
        $limit = $request->query->get('limit', 50000);
        $page = $request->query->get('page', 1);
        $inventoryId = $request->query->get('inventoryId', null);
        $transactionType = $request->query->get('transactionType', null);
        $from = $request->query->get('from', null);
        $to = $request->query->get('to', null);
        $result = $transactionService->queryByInventoryAndTransactionTypeInPeriod($inventoryId, $transactionType, $from, $to, $limit, $page);
        return $this->json($result);
    }

}
