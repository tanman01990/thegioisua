<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Category;
use App\Service\ProductService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController
{
    #[Route('/api/product', name: 'get_product', methods: ['GET'])]
    public function index(ProductService $productService, Request $request): JsonResponse
    {
        $limit = $request->query->get('limit', 10);
        $page = $request->query->get('page', 1);
        $pagination = $productService->findAllWithPagination($limit, $page);
        return $this->json($pagination);
    }

    #[Route('/product', name: 'app_product', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Create a new Product entity
        $product = new Product();

        // Create a new Category entity
        $category = new Category();
        $category->setName('Category Name');

        // Associate the Product with the Category
        $product->setCategory($category);
        $product->setName('Product Name');
        $product->setPrice(9.99);

        // Persist the entities
        $entityManager->persist($category);
        $entityManager->persist($product);
        $entityManager->flush();

        return new Response('Product created successfully');
    }
}
