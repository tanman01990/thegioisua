<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Category;
use App\Service\ProductService;
use App\Service\TransactionService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
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

    #[Route('/api/delete-transaction', name: 'delete_transaction', methods: ['POST'])]
    public function delete(TransactionService $transactionService, Request $request): JsonResponse
    {

        try {
            $content = $request->getContent();
            $postData = json_decode($content, true);
            $startDateInput = $postData['from'];
            $endDateInput = $postData['to'];
            $startDate = new \DateTime($startDateInput . ' 00:00:00');
            $endDate = new \DateTime($endDateInput . ' 23:59:59');
            $result = $transactionService->deleteDataBetweenDates($startDate, $endDate);
            return $this->json(["data" => $result]);
        } catch (Exception $ex) {
            return $this->json(["data" => $ex->getMessage()]);
        }
    }
}
