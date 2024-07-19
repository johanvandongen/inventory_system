<?php

namespace App\Repository;

use App\Entity\Image;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Image>
 */
class ImageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Image::class);
    }

    public function findProductMostRecentImage($productId): array
       {
        $qb = $this->createQueryBuilder('i');
           return $qb
           ->where('i.product = :productId')
           ->setParameter('productId', $productId)
           ->setMaxResults(2)->getQuery()->getResult()
           ;
       }
}
