<?php

namespace App\Controller;

use App\DTO\FruitDTO;
use App\DTO\VegetableDTO;
use App\Entity\Fruit;
use App\Entity\Product;
use App\Entity\Vegetable;
use App\Service\CollectionService;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
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
    #[OA\Get(description: 'List of Fruits')]
    #[OA\QueryParameter(
        name: 'name',
        in: 'query',
        required: false,
        description: 'Optional name to search',
        schema: new OA\Schema(type: 'string', default: '')
    )]
    #[OA\Response(
        response: 200,
        description: 'Return list of fruits',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(
                    property: 'fruits',
                    type: 'array',
                    items: new OA\Items(ref: new Model(type: Fruit::class))
                )
            ]
        )
    )]
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
    #[OA\Get(description: 'List of Vegetables')]
    #[OA\QueryParameter(
        name: 'name',
        in: 'query',
        required: false,
        description: 'Optional name to search',
        schema: new OA\Schema(type: 'string', default: '')
    )]
    #[OA\Response(
        response: 200,
        description: 'Return list of vegtables',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(
                    property: 'vegetables',
                    type: 'array',
                    items: new OA\Items(ref: new Model(type: Vegetable::class))
                )
            ]
        )
    )]
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
    #[OA\Get(description: 'List of Products Fruits|Vegetables')]
    #[OA\QueryParameter(
        name: 'name',
        in: 'query',
        required: false,
        description: 'Optional name to search',
        schema: new OA\Schema(type: 'string', default: '')
    )]
    #[OA\Response(
        response: 200,
        description: 'Return list of products',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(
                    property: 'products',
                    type: 'array',
                    items: new OA\Items(ref: new Model(type: Product::class))
                )
            ]
        )
    )]
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
    #[OA\Get(description: 'Get a Fruit by id')]
    #[OA\Response(
        response: 200,
        description: 'Return a Fruit',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(
                    property: 'fruit',
                    ref: new Model(type: Fruit::class)
                )

            ]
        )
    )]
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
    #[OA\Get(description: 'Get a Vegetable by id')]
    #[OA\Response(
        response: 200,
        description: 'Return a Vegetable',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(
                    property: 'vegetable',
                    ref: new Model(type: Vegetable::class)
                )

            ]
        )
    )]
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
    #[OA\Get(description: 'Get a Product by id')]
    #[OA\Response(
        response: 200,
        description: 'Return a Product',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(
                    property: 'product',
                    ref: new Model(type: Product::class)
                )

            ]
        )
    )]
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
    #[OA\Post(description: 'Create a fruit')]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: 'name', type: 'string', example: 'Carrot'),
                new OA\Property(property: 'quantity', type: 'integer', example: 5),
                new OA\Property(property: 'unit', type: 'string', example: 'g')
            ]
        )
    )]
    #[OA\Response(
        response: 201,
        description: 'Successful',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                    new OA\Property(property: 'status', type: 'string', example: 'success'),
                    new OA\Property(property: 'message', type: 'string', example: 'Fruit created successfully')
                ]
        )
    )
    ]
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
    #[OA\Post(description: 'Create a vagatable')]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: 'name', type: 'string', example: 'Carrot'),
                new OA\Property(property: 'quantity', type: 'integer', example: 5),
                new OA\Property(property: 'unit', type: 'string', example: 'g')
            ]
        )
    )]
    #[OA\Response(
        response: 201,
        description: 'Successful',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                    new OA\Property(property: 'status', type: 'string', example: 'success'),
                    new OA\Property(property: 'message', type: 'string', example: 'Vegetable created successfully')
                ]
        )
    )
    ]
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

    #[Route('/api/process/json', name: 'api_process_json', methods: ['GET'])]
    #[OA\Get(description: 'Process json file')]
    #[OA\Response(
        response: 200,
        description: 'Return list of products',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(
                    property: 'products',
                    type: 'array',
                    items: new OA\Items(ref: new Model(type: Product::class))
                )
            ]
        )
    )]
    public function processJson(): JsonResponse {
        $jsonFilePath = __DIR__ . '/../../request.json';

        try {
            $products = $this->collectionService->addFromFile($jsonFilePath);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }

        return $this->json([
            'products' => array_map(fn($product) => $product->toArray(), $products),
        ]);
    }

    #[Route('/api/health', name: 'api_health_check', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'API is healthy',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                    new OA\Property(property: 'status', type: 'string', example: 'ok'),
                    new OA\Property(property: 'message', type: 'string', example: 'API is healthy')
                ]
        )
    )
    ]
    public function healthCheck(): JsonResponse
    {
        return $this->json([
            'status' => 'ok',
            'message' => 'API is healthy',
        ]);
    }
}
