<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Price;
use App\Repository\CategoryRepository;
use App\Repository\PriceRepository;
use App\Request\PriceRequest;

class PriceUpdater
{
    private CategoryRepository $categoryRepository;
    private PriceRepository $priceRepository;

    public function __construct(
        CategoryRepository $categoryRepository,
        PriceRepository $priceRepository
    ) {
        $this->categoryRepository = $categoryRepository;
        $this->priceRepository = $priceRepository;
    }

     public function processPriceRequest(PriceRequest $priceRequest): void
     {
         $category = $this->categoryRepository->findOneByName($priceRequest->getCategory());
         $price = $this->priceRepository->findOneByPriceRequest($priceRequest);
         if (null === $price) {
             $price = new Price(
                 $priceRequest->getCurrency(),
                 $priceRequest->getProduct(),
                 $priceRequest->getVariant(),
                 $category,
             );
         }
         $price->setPrice($priceRequest->getPrice());
         $this->priceRepository->save($price);
     }
}
