<?php

namespace App\Repository;

use App\Entity\Sale;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Sale>
 */
class SaleRepository extends ServiceEntityRepository
{
    private EntityManagerInterface $entityManager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, Sale::class);
        $this->entityManager = $entityManager;
    }


    /**
     * Función que recupera los datos de la venta, el nombde de usuario, el nombre del modelo y el nombre de la marca del coche
     *
     * @author Pablo López Gosálvez <i92logop@uco.es>
     *
     * @return Query
     */
    public function findSales(): Query
    {
        $dql = '
            SELECT s, u.username, car, carModel, carBrand
            FROM App\Entity\Sale s
            JOIN s.user u
            JOIN s.car car
            JOIN car.carModel carModel
            JOIN carModel.carBrand carBrand
        ';

        return $this->entityManager->createQuery($dql);
    }

    //    /**
    //     * @return Sale[] Returns an array of Sale objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('s.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Sale
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
