<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    private EntityManagerInterface $entityManager;

    /**
     * @param ManagerRegistry $registry
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, User::class);
        $this->entityManager = $entityManager;
    }


    /**
     * Función que recupera todos los datos de los usuarios
     *
     * @author Pablo López Gosálvez <i92logop@uco.es>
     * @return Query
     */
    public function getUsers(): Query
    {
        $dql = '
            SELECT u
            FROM App\Entity\User u
        ';

        return $this->entityManager->createQuery($dql);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    /**
     * Función que recupera la contraseña de un usuario
     *
     * @author Pablo López Gosálvez <i92logop@uco.es>
     *
     * @param $id
     * @return array|bool $currentPassword
     * @throws Exception
     * @throws \Throwable
     */
    public function getCurrentPassword($id): array|bool
    {
        $sql =
            'SELECT 
                u.password
            FROM 
                user u
            WHERE 
                u.id = :id'
        ;

        $parameters = ['id' => $id];

        try {
            $connection = $this->entityManager->getConnection();
            $query = $connection->prepare($sql);
            $resultQuery = $query->executeQuery($parameters);
            $currentPassword = $resultQuery->fetchAssociative();
        } catch (\Throwable $th) {
            throw $th;
        }

        return $currentPassword;
    }

    /**
     * Función que actualiza la contraseña de un usuario
     *
     * @author Pablo López Gosálvez <i92logop@uco.es>
     *
     * @param $user
     * @param $encodedPassword
     * @param $needPersist
     * @return bool
     */
    public function update($user, $encodedPassword, $needPersist = false): bool
    {
        try {
            $user->setPassword($encodedPassword);

            if ($needPersist) {
                $this->entityManager->persist($user);
            }

            $this->entityManager->flush();
        } catch (\Throwable $th) {
            return false;
        }

        return true;
    }

    /**
     * Función que elimina un usuario de la base de datos
     *
     * @author Pablo López Gosálvez <i92logop@uco.es>
     *
     * @param $user
     * @return bool
     */
    public function remove($user): bool
    {
        try {
            $this->entityManager->remove($user);
            $this->entityManager->flush();
        } catch (\Throwable $th) {
            return false;
        }

        return true;
    }

    //    /**
    //     * @return User[] Returns an array of User objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('u.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?User
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
