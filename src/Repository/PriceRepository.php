<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Price;
use App\Request\PriceRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Price>
 *
 * @method null|Price find($id, $lockMode = null, $lockVersion = null)
 * @method null|Price findOneBy(array $criteria, array $orderBy = null)
 * @method Price[]    findAll()
 * @method Price[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PriceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Price::class);
    }

    public function save(Price $price): void
    {
        $this->getEntityManager()->persist($price);
        $this->getEntityManager()->flush();
        if ($price->getCategory()->isUniform()) {
            $variantsPrices = $this->findPricesForProduct($price->getProduct(), $price->getCurrency());
            foreach ($variantsPrices as $variantPrice) {
                $variantPrice->setPrice($price->getPrice());
            }
        }
        $this->getEntityManager()->flush();
    }

    public function findOneByPriceRequest(PriceRequest $priceRequest): ?Price
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.product = :p')
            ->andWhere('p.variant = :v')
            ->andWhere('p.currency = :c')
            ->setParameter('p', $priceRequest->getProduct())
            ->setParameter('v', $priceRequest->getVariant())
            ->setParameter('c', $priceRequest->getCurrency())
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /**
     * @return Price[]
     */
    public function findPricesForProduct(string $product, string $currency): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.product = :p')
            ->andWhere('p.currency = :c')
            ->setParameter('p', $product)
            ->setParameter('c', $currency)
            ->getQuery()
            ->getResult()
        ;
    }
}
