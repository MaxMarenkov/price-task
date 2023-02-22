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

    protected function setUp(): void
    {
        static::ensureKernelShutdown();
        $this->client = static::createClient();
        $this->client->disableReboot();

        parent::setUp();
    }

     public function testController(): void
     {
         $this->client->request(Request::METHOD_POST, '/prices');
         $this->assertResponseStatusCodeSame(Response::HTTP_OK);
     }
}
