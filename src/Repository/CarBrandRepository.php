<?php

namespace App\Repository;

use App\Entity\CarBrand;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CarBrand>
 */
class CarBrandRepository extends ServiceEntityRepository
{
    private EntityManagerInterface $entityManager;

    /**
     * @param ManagerRegistry $registry
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(ManagerRegistry $registry,EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, CarBrand::class);
        $this->entityManager = $entityManager;
    }


    /**
     * Función que elimina una marca de coche
     *
     * @author Pablo López Gosálvez <i92logop@uco.es>
     *
     * @param $carBrand
     * @return bool
     */
    public function remove($carBrand): bool
    {
        try {
            $this->entityManager->remove($carBrand);
            $this->entityManager->flush();
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }

    //    /**
    //     * @return CarBrand[] Returns an array of CarBrand objects
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

    //    public function findOneBySomeField($value): ?CarBrand
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
