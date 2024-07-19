<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

use function Symfony\Component\DependencyInjection\Loader\Configurator\expr;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    //    /**
    //     * @return Product[] Returns an array of Product objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

       public function findAllOneState(): array
       {
        // return $this->findAll();
        $qb = $this->createQueryBuilder('p');
           return $qb
           ->leftJoin('p.state', 's')->addSelect('s')
           ->addOrderBy('p.name')->addOrderBy('s.date', 'DESC')->getQuery()->getResult()

            //    ->setMaxResults(10)
           ;
       }

       public function findProductMostRecentState($productId): array
       {
        // return $this->findAll();
        $qb = $this->createQueryBuilder('p');
           return $qb
        //    ->addSelect('s.state')
           ->where('p.id = :productId')
           ->setParameter('productId', $productId)
           ->leftJoin('p.state', 's')->addSelect('s')
           ->addOrderBy('s.date', 'DESC')->setMaxResults(2)->getQuery()->getResult()
           ;
       }

    //    public function findOneBySomeField($value): ?Product
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
