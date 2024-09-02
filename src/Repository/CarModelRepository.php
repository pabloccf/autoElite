<?php

namespace App\Repository;

use App\Entity\CarModel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CarModel>
 */
class CarModelRepository extends ServiceEntityRepository
{
    private EntityManagerInterface $entityManager;

    /**
     * @param ManagerRegistry $registry
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, CarModel::class);
        $this->entityManager = $entityManager;
    }

    /**
     * Función que recupera todos los datos de los modelos si no esta eliminado
     *
     * @author Pablo López Gosálvez <i92logop@uco.es>
     *
     * @return Query
     */
    public function findAllNotDeleted(): Query
    {
        $dql = '
            SELECT cm, cb.name as brand_name
            FROM App\Entity\CarModel cm
            JOIN cm.carBrand cb
            WHERE cm.isDeleted = :isDeleted
        ';

        $parameters = ['isDeleted' => 0];

        $query = $this->entityManager->createQuery($dql);
        $query->setParameters($parameters);

        return $query;
    }

    /**
     * Función que edita los datos de un modelo de un coche
     *
     * @author Pablo López Gosálvez <i92logop@uco.es>
     *
     * @param $carModel
     * @param $needPersist
     * @return bool
     */
    public function update($carModel, $needPersist = false): bool
    {
        try {
            if ($needPersist) {
                $this->entityManager->persist($carModel);
            }

            $this->entityManager->flush();
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }

    /**
     * Función que elimina un modelo de un coche
     *
     * @author Pablo López Gosálvez <i92logop@uco.es>
     *
     * @param $carModel
     * @return bool
     */
    public function remove($carModel): bool
    {
        try {
            $carModel->setDeleted(true);
            $this->entityManager->persist($carModel);
            $this->entityManager->flush();
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }

    //    /**
    //     * @return CarModel[] Returns an array of CarModel objects
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

    //    public function findOneBySomeField($value): ?CarModel
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
