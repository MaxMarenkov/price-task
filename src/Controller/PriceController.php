<?php

declare(strict_types=1);

namespace App\Controller;

use App\Request\PriceRequest;
use App\Request\RequestConverter;
use App\Service\PriceUpdater;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

class PriceController extends AbstractController
{
    private RequestConverter $converter;
    private PriceUpdater $priceUpdater;

    public function __construct(RequestConverter $converter, PriceUpdater $priceUpdater)
    {
        $this->converter = $converter;
        $this->priceUpdater = $priceUpdater;
    }

    #[Route('/prices', name: 'prices', methods: ['POST'])]
    public function index(Request $request): JsonResponse
    {
        try {
            /** @var PriceRequest $priceRequest */
            $priceRequest = $this->converter->convertRequest($request, PriceRequest::class);
        } catch (BadRequestHttpException $e) {
            return new JsonResponse(status: Response::HTTP_BAD_REQUEST);
        }
        $this->priceUpdater->processPriceRequest($priceRequest);

        return $this->json(['price' => $priceRequest->getPrice()], Response::HTTP_OK);
    }
}
