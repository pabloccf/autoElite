<?php

namespace App\Repository;

use App\Entity\Car;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Car>
 */
class CarRepository extends ServiceEntityRepository
{
    private EntityManagerInterface $entityManager;

    /**
     * @param ManagerRegistry $registry
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, Car::class);
        $this->entityManager = $entityManager;
    }


    /**
     * Función que recupera algunos datos del coche, del modelo y de la marca
     *
     * @author Pablo López Gosálvez <i92logop@uco.es>
     *
     * @return Query
     */
    public function findNotDeleted(): Query
    {
        $dql = '
            SELECT c.id, c.plate, c.manufacture_year, cm.name AS model_name, cm.image AS model_image, cb.name AS brand_name
            FROM App\Entity\Car c
            JOIN c.carModel cm
            JOIN cm.carBrand cb
            WHERE c.isDeleted = :isDeleted
        ';

        $parameters = ['isDeleted' => 0];

        $query = $this->entityManager->createQuery($dql);
        $query->setParameters($parameters);

        return $query;
    }

    /**
     * Función que elimina un coche
     *
     * @author Pablo López Gosálvez <i92logop@uco.es>
     *
     * @param $car
     * @return bool
     */
    public function remove($car): bool
    {
        try {
            $car->setDeleted(true);
            $this->entityManager->persist($car);
            $this->entityManager->flush();
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }

    //    /**
    //     * @return Car[] Returns an array of Car objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Car
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
