<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\Entity\Price;
use App\Repository\PriceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Generator;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PriceControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private ?EntityManagerInterface $em;
    private ?PriceRepository $priceRepository;

    protected function setUp(): void
    {
        static::ensureKernelShutdown();
        $this->client = static::createClient();
        $this->client->disableReboot();

        $this->em = static::getContainer()->get(EntityManagerInterface::class);
        $this->em->getConnection()->beginTransaction();
        $this->priceRepository = $this->em->getRepository(Price::class);
        parent::setUp();
    }

    protected function tearDown(): void
    {
        $this->em->getConnection()->rollBack();
        parent::tearDown();
    }

    public function negativeDataProvider(): Generator
    {
        yield 'empty request' => [[]];

        yield 'invalid currency' => [[
            'price' => 10000,
            'currency' => 'xxx',
            'variant' => '42',
            'product' => 'Adidas Shoes',
            'category' => 'Shoes',
        ]];

        yield 'extra field' => [[
            'price' => 10000,
            'currency' => 'USD',
            'variant' => '42',
            'product' => 'Adidas Shoes',
            'category' => 'Shoes',
            'extra' => 'extra',
        ]];

        yield 'missing field' => [[
            'price' => 10000,
            'product' => 'Adidas Shoes',
            'category' => 'Shoes',
        ]];
    }

     /**
      * @dataProvider negativeDataProvider
      */
     public function testNegativeScenarios(array $data): void
     {
         $this->client->request(Request::METHOD_POST, '/prices', $data);
         $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
     }

    public function testUnifiedCategory(): void
    {
        $this->client->jsonRequest(Request::METHOD_POST, '/prices', [
            'price' => $price = 12000,
            'currency' => $currency = 'USD',
            'variant' => $variant = '42',
            'product' => $product = 'Adidas',
            'category' => 'Shoes',
        ]);
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        /** @var Price[] $entities */
        $entities = $this->priceRepository->findBy([
            'currency' => $currency,
            'variant' => $variant,
            'product' => $product,
        ]);

        $this->assertCount(1, $entities);
        $this->assertEquals($price, array_pop($entities)->getPrice());

        $this->client->jsonRequest(Request::METHOD_POST, '/prices', [
            'price' => $price = 9900,
            'currency' => $currency,
            'variant' => '40',
            'product' => $product,
            'category' => 'Shoes',
        ]);

        /** @var Price[] $entities */
        $entities = $this->priceRepository->findBy([
            'currency' => $currency,
            'product' => $product,
        ]);

        $this->assertCount(2, $entities);
        $this->assertEquals($price, array_pop($entities)->getPrice());
        $this->assertEquals($price, array_pop($entities)->getPrice());
    }

    public function testNonUnifiedCategory(): void
    {
        $this->client->jsonRequest(Request::METHOD_POST, '/prices', [
            'price' => $priceA = 12000,
            'currency' => $currency = 'EUR',
            'variant' => 'long',
            'product' => $product = 'Jewelry product',
            'category' => 'Jewelry',
        ]);
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->client->jsonRequest(Request::METHOD_POST, '/prices', [
            'price' => $priceB = 9900,
            'currency' => $currency,
            'variant' => 'short',
            'product' => $product,
            'category' => 'Jewelry',
        ]);
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        /** @var Price[] $entities */
        $entities = $this->priceRepository->findBy([
            'currency' => $currency,
            'product' => $product,
        ]);

        $this->assertCount(2, $entities);
        $this->assertEquals($priceB, array_pop($entities)->getPrice());
        $this->assertEquals($priceA, array_pop($entities)->getPrice());
    }
}
