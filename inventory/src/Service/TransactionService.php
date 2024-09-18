<?php

namespace App\Service;

use App\Entity\Product;
use App\Entity\Category;
use App\Entity\Customer;
use App\Entity\InventoryTransaction;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use App\Repository\InventoryTransactionRepository;

class TransactionService
{
    private $entityRepository;
    private $entityManager;
    private $paginator;

    public function __construct(InventoryTransactionRepository $entityRepository, PaginatorInterface $paginator ,EntityManagerInterface $entityManager)
    {
        $this->entityRepository = $entityRepository;
        $this->entityManager = $entityManager;
        $this->paginator = $paginator;
    }

    public function findAll()
    {
        return $this->entityRepository->findAll();
    }

    public function findAllWithPagination($type, $limit, $page)
    {
        $queryBuilder = $this->entityRepository->createQueryBuilder('p');

        $pagination = $this->paginator->paginate(
            $queryBuilder, /* query NOT result */
            $page, /* page number */
            $limit /* limit per page */
        );
        $count = $this->countAllTransaction($type);
        return [
            'items' => $pagination->getItems(),
            'pagination' => [
                'currentPage' => $pagination->getCurrentPageNumber(),
                'totalPages' => round($count/$limit) + ($count%$limit ? 1 : 0),
                'totalItems' => $pagination->getTotalItemCount(),
            ],
        ];
    }

    public function countAllTransaction($type): int
    {
        return $this->entityRepository->countAllTransaction($type);
    }

    public function processExcelFile(string $filePath)
    {
        $spreadsheet = IOFactory::load($filePath);
        $sheet = $spreadsheet->getActiveSheet();

        foreach ($sheet->getRowIterator() as $rowIndex => $row) {
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);

            $data = [];
            foreach ($cellIterator as $cell) {
                $data[] = $cell->getValue();
            }

            if ($rowIndex == 1) {
                // Assuming first row is the header, you can handle it differently if required
                continue;
            }

            // Assuming the first column is category, the second is product, and the third is customer
            $categoryName = $data[0];
            $productName = $data[1];
            $customerName = $data[2];

            // Process the data and save to the database
            $category = $this->entityManager->getRepository(Category::class)->findOneBy(['name' => $categoryName]);
            if (!$category) {
                $category = new Category();
                $category->setName($categoryName);
                $this->entityManager->persist($category);
            }

            $product = $this->entityManager->getRepository(Product::class)->findOneBy(['name' => $productName]);
            $product = new Product();
            $product->setName($productName);
            $product->setCategory($category);
            $this->entityManager->persist($product);

            $customer = new Customer();
            $customer->setName($customerName);
            $this->entityManager->persist($customer);
        }

        $this->entityManager->flush();
    }

    // Query By inventory and Transaction Type in Period time but condition can dynamic i mean we can null or not null
    public function queryByInventoryAndTransactionTypeInPeriod($inventoryId, $transactionType, $from, $to, $limit = 100, $page = 0)
    {
        $count = $this->countAllTransaction($transactionType);
        $queryBuilder = $this->entityRepository->createQueryBuilder('p');
        //query by inventory
        if ($inventoryId !== null) {
            $queryBuilder->andWhere('p.inventory = :inventoryId')
                ->setParameter('inventoryId', $inventoryId);
        }
        //query by transaction type
        if ($transactionType !== null) {
            $queryBuilder->andWhere('p.transactionType = :transactionType')
                ->setParameter('transactionType', $transactionType);
        }
        //query by date
        if ($from !== null && $to !== null) {
            $queryBuilder->andWhere('p.transactionDate >= :from')
                ->andWhere('p.transactionDate <= :to')
                ->setParameter('from', $from)
                ->setParameter('to', $to);
        }
        //query by limit
        if ($limit !== null) {
            $queryBuilder->setMaxResults($limit);
        }
        //query by page
        if ($page !== null) {
            $queryBuilder->setFirstResult($page);
        }

        $pagination = $this->paginator->paginate(
            $queryBuilder, /* query NOT result */
            $page, /* page number */
            $limit /* limit per page */
        );
        return [
            'items' => $pagination->getItems(),
            'pagination' => [
                'currentPage' => $pagination->getCurrentPageNumber(),
                'totalPages' => round($count/$limit) + ($count%$limit ? 1 : 0),
                'totalItems' =>  $count,
            ],
        ];

        // return [
        //     'items' => $this->entityRepository->queryByInventoryAndTransactionTypeInPeriod($inventoryId, $transactionType, $from, $to, $limit, $page),
        //     'pagination' => [
        //         'currentPage' => 0,
        //         'totalPages' => round($count/$limit) + ($count%$limit ? 1 : 0),
        //         'totalItems' =>  $count,
        //     ],
        // ];
    }

    // I want to add bunch of inventory transaction here
    public function addInventoryTransactions(array $transactions)
    {
        foreach ($transactions as $transaction) {
            $product = $this->entityManager->getRepository(Product::class)->findOneBy(['name' => $transaction['product']]);
            $customer = $this->entityManager->getRepository(Customer::class)->findOneBy(['name' => $transaction['customer']]);
            
            if (!$product || !$customer) {
                continue;
            }
            
            $inventoryTransaction = new InventoryTransaction();
            $inventoryTransaction->setProduct($product);
            $inventoryTransaction->setCustomer($customer);
            $inventoryTransaction->setQuantity($transaction['quantity']);
            $inventoryTransaction->setTransactionType($transaction['type']);
            $this->entityManager->persist($inventoryTransaction);
        }
        
        $this->entityManager->flush();
    }

    

    // Define other methods as needed, e.g., findById, create, update, delete
}