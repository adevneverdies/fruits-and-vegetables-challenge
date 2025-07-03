<?php

namespace App\Tests\Integration\Controller;

use App\Tests\Integration\Fixture\DataFixture;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class ApiTest extends WebTestCase
{
    use ResetDatabase; use Factories;

    private ?KernelBrowser $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient();
        $entityManager = self::getContainer()->get('doctrine')->getManager();

        $fixture = new DataFixture();
        $fixture->load($entityManager);
    }

    public function testApiFruitsList(): void
    {
        $this->client->request('GET', '/api/fruits');
        $this->assertResponseIsSuccessful();

        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('fruits', $responseData);
        $this->assertNotEmpty($responseData['fruits']);
        $this->assertCount(1, $responseData['fruits'], 'Expected 1 fruits in the response');
    }

    public function testApiVegetablesList(): void
    {
        $this->client->request('GET', '/api/vegetables');
        $this->assertResponseIsSuccessful();

        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('vegetables', $responseData);
        $this->assertNotEmpty($responseData['vegetables']);
        $this->assertCount(1, $responseData['vegetables'], 'Expected 1 vegetables in the response');

    }

    public function testApiProductsList(): void
    {
        $this->client->request('GET', '/api/products');
        $this->assertResponseIsSuccessful();

        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('products', $responseData);
        $this->assertNotEmpty($responseData['products']);
        $this->assertCount(2, $responseData['products'], 'Expected 2 products in the response');
    }

    public function testApiFruitDetail(): void
    {
        $this->client->request('GET', '/api/fruits/1');
        $this->assertResponseIsSuccessful();

        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('fruit', $responseData);
        $this->assertNotEmpty($responseData['fruit']);
        $this->assertEquals('Apple', $responseData['fruit']['name'], 'Expected fruit name to be Apple');
    }

    public function testApiVegetableDetail(): void
    {
        $this->client->request('GET', '/api/vegetables/2');
        $this->assertResponseIsSuccessful();

        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('vegetable', $responseData);
        $this->assertNotEmpty($responseData['vegetable']);
        $this->assertEquals('Carrot', $responseData['vegetable']['name'], 'Expected vegetable name to be Carrot');
    }

    public function testApiProductDetail(): void
    {
        $this->client->request('GET', '/api/products/1');
        $this->assertResponseIsSuccessful();

        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('product', $responseData);
        $this->assertNotEmpty($responseData['product']);
        $this->assertEquals('Apple', $responseData['product']['name'], 'Expected product name to be Apple');
        $this->assertEquals('fruit', $responseData['product']['type'], 'Expected product type to be fruit');

    }

    public function testApiCreateFruit(): void
    {
        $this->client->request('POST', '/api/fruits', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode(['name' => 'Banana', 'quantity' => 20.0]));
        $this->assertResponseIsSuccessful();

        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals('success', $responseData['status'] ?? null, 'Expected status to be success');

    }

    public function testApiCreateVegetable(): void
    {
        $this->client->request('POST', '/api/vegetables', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode(['name' => 'Broccoli', 'quantity' => 15.0]));
        $this->assertResponseIsSuccessful();

        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals('success', $responseData['status'] ?? null, 'Expected status to be success');
    }

    public function testApiFilterByName(): void
    {
        $this->client->request('POST', '/api/fruits', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode(['name' => 'Banana', 'quantity' => 20.0]));
        $this->assertResponseIsSuccessful();

        $this->client->request('GET', '/api/fruits?name=Bana');
        $this->assertResponseIsSuccessful();
        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('fruits', $responseData);
        $this->assertNotEmpty($responseData['fruits']);
        $this->assertCount(1, $responseData['fruits'], 'Expected 1 fruit in the response after filtering by name');
        $this->assertEquals('Banana', $responseData['fruits'][0]['name'], 'Expected filtered fruit name to be Banana');
    }

    public function testApiHealthcheck(): void
    {
        $this->client->request('GET', '/api/health');
        $this->assertResponseIsSuccessful();

        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals('ok', $responseData['status'] ?? null, 'API health check failed');
    }

    public function tearDown(): void
    {
        parent::tearDown();
        $this->client = null; // Avoid memory leaks
    }
}
