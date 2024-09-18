<?php

namespace App\Service;

use App\Entity\Product;
use App\Entity\Category;
use App\Entity\Customer;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;

class ProductService
{
    private $entityRepository;
    private $entityManager;
    private $paginator;

    public function __construct(ProductRepository $entityRepository, PaginatorInterface $paginator ,EntityManagerInterface $entityManager)
    {
        $this->entityRepository = $entityRepository;
        $this->entityManager = $entityManager;
        $this->paginator = $paginator;
    }

    public function findAll()
    {
        return $this->entityRepository->findAll();
    }

    public function findAllWithPagination($limit, $page)
    {
        $queryBuilder = $this->entityRepository->createQueryBuilder('p');

        $pagination = $this->paginator->paginate(
            $queryBuilder, /* query NOT result */
            $page, /* page number */
            $limit /* limit per page */
        );

        return [
            'items' => $pagination->getItems(),
            'pagination' => [
                'currentPage' => $pagination->getCurrentPageNumber(),
                'totalPages' => round($this->countAllProducts()/$limit) + ($this->countAllProducts()%$limit ? 1 : 0),
                'totalItems' => $pagination->getTotalItemCount(),
            ],
        ];
    }

    public function countAllProducts(): int
    {
        return $this->entityRepository->countAllProducts();
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
    

    // Define other methods as needed, e.g., findById, create, update, delete
}