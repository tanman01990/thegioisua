<?php

namespace App\Controller;

use App\Service\CategoryService;
use App\Repository\CategoryRepository;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryController extends AbstractController
{
    #[Route('/category', name: 'app_category')]
    public function index(CategoryService $categoryService): JsonResponse
    {
        // return $this->json([
        //     'message' => 'Welcome to your new controller!',
        //     'path' => 'src/Controller/CategoryController.php',
        // ]);
        $entities = $categoryService->findAll();
        return $this->json($entities);
    }
}
