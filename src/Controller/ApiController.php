<?php

namespace App\Controller;

use App\DTO\FruitDTO;
use App\DTO\VegetableDTO;
use App\Service\CollectionService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    public function __construct(
        private CollectionService $collectionService
    )
    {}

    #[Route('/api/fruits', name: 'api_fruit_list', methods: ['GET'])]
    public function fruits(Request $request): JsonResponse
    {
        $name = $request->query->get('name', '');

        try {
            if (! empty($name)) {
                $fruits = $this->collectionService->filterFruitsByName($name);
            } else {
                $fruits = $this->collectionService->listFruits();
            }

            return $this->json([
                'fruits' => array_map(fn($fruit) => $fruit->toArray(), $fruits),
            ]);

        } catch (\RuntimeException $e) {
            return $this->json([
                'error' => 'An error occurred while processing your request.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    #[Route('/api/vegetables', name: 'api_vegetables_list', methods: ['GET'])]
    public function vegetables(Request $request): JsonResponse
    {
        $name = $request->query->get('name', '');

        try {
            if (! empty($name)) {
                $vegetables = $this->collectionService->filterVegetablesByName($name);
            } else {
                $vegetables = $this->collectionService->listVegetables();
            }

            return $this->json([
                'vegetables' => array_map(fn($vegetable) => $vegetable->toArray(), $vegetables),
            ]);

        } catch (\RuntimeException $e) {
            return $this->json([
                'error' => 'An error occurred while processing your request.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    #[Route('/api/products', name: 'api_prodcuts_list', methods: ['GET'])]
    public function products(Request $request): JsonResponse
    {
        $name = $request->query->get('name', '');        

        try {
            if (! empty($name)) {
                $prodcuts = $this->collectionService->filterProductsByName($name);
            } else {
                $prodcuts = $this->collectionService->listProducts();
            }

            return $this->json([
                'products' => array_map(fn($prodcut) => $prodcut->toArray(), $prodcuts),
            ]);

        } catch (\RuntimeException $e) {
            return $this->json([
                'error' => 'An error occurred while processing your request.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    #[Route('/api/fruits/{id}', name: 'api_fruit_get', methods: ['GET'])]
    public function getFruit(int $id): JsonResponse
    {
        try {
            $fruit = $this->collectionService->getFruitById($id);
            if ($fruit === null) {
                return $this->json(['error' => 'Fruit not found'], 404);
            }

            return $this->json([
                'fruit' => $fruit->toArray(),
            ]);

        } catch (\RuntimeException $e) {
            return $this->json([
                'error' => 'An error occurred while processing your request.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    #[Route('/api/vegetables/{id}', name: 'api_vegetable_get', methods: ['GET'])]
    public function getVegetable(int $id): JsonResponse
    {
        try {
            $vegetable = $this->collectionService->getVegetableById($id);
            if ($vegetable === null) {
                return $this->json(['error' => 'Vegetable not found'], 404);
            }

            return $this->json([
                'vegetable' => $vegetable->toArray(),
            ]);

        } catch (\RuntimeException $e) {
            return $this->json([
                'error' => 'An error occurred while processing your request.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    #[Route('/api/products/{id}', name: 'api_product_get', methods: ['GET'])]
    public function getProduct(int $id): JsonResponse
    {
        try {
            $product = $this->collectionService->getProductById($id);
            if ($product === null) {
                return $this->json(['error' => 'Product not found'], 404);
            }
            return $this->json([
                'product' => $product->toArray(),
            ]);
        } catch (\RuntimeException $e) {
            return $this->json([
                'error' => 'An error occurred while processing your request.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    #[Route('/api/fruits', name: 'api_fruit_create', methods: ['POST'], defaults: ['_format' => 'json'])]
    public function createFruit(Request $request): JsonResponse
    {

        $jsonContent = $request->getContent();
        if (empty($jsonContent)) {
            return $this->json(['error' => 'Request body cannot be empty'], 400);
        }

        $data = json_decode($jsonContent);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return $this->json(['error' => 'Invalid JSON format'], 400);
        }

        $dto = new FruitDTO(
            $data->name ?? '',
            $data->quantity ?? 0,
            $data->unit ?? 'g'
        );

        $errors = $dto->validate();
        if (count($errors) > 0) {
            return $this->json(['errors' => $errors], 400);
        }

        $dto->convertUnitToGrams(); // Convert unit to grams if necessary

        try {
            $this->collectionService->addFruit($dto);
        } catch (\RuntimeException $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }

        return $this->json([
            'status' => 'success',
            'message' => 'Fruit created successfully',
        ], 201);
    }

    #[Route('/api/vegetables', name: 'api_vegetable_create', methods: ['POST'], defaults: ['_format' => 'json'])]
    public function createVegetable(Request $request): JsonResponse
    {
        $jsonContent = $request->getContent();
        if (empty($jsonContent)) {
            return $this->json(['error' => 'Request body cannot be empty'], 400);
        }

        $data = json_decode($jsonContent);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return $this->json(['error' => 'Invalid JSON format'], 400);
        }

        $dto = new VegetableDTO(
            $data->name ?? '',
            $data->quantity ?? 0,
            $data->unit ?? 'g'
        );

        $errors = $dto->validate();
        if (count($errors) > 0) {
            return $this->json(['errors' => $errors], 400);
        }

        $dto->convertUnitToGrams(); // Convert unit to grams if necessary
        $this->collectionService->addVegetable($dto);

        return $this->json([
            'status' => 'success',
            'message' => 'Vegetable created successfully',
        ], 201);
    }

    #[Route('/api/health', name: 'api_health_check', methods: ['GET'])]
    public function healthCheck(): JsonResponse
    {
        return $this->json([
            'status' => 'ok',
            'message' => 'API is healthy',
        ]);
    }
}
