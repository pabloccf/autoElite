<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
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
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    /**
     * Función que recupera la contraseña de un usuario
     *
     * @author Pablo López Gosálvez <i92logop@uco.es>
     *
     * @param $id
     * @return $currentPassword
     * @throws Exception
     * @throws \Throwable
     */
    public function getCurrentPassword($id)
    {
        $sql =
            'SELECT 
                u.password
            FROM 
                users u
            WHERE 
                u.id = :id'
        ;

        $parameters = ['id' => $id];

        try {
            $connection = $this->getEntityManager()->getConnection();
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
    public function update($user, $encodedPassword, $needPersist = false)
    {
        try {
            $entityManager = $this->getEntityManager();

            $user->setPassword($encodedPassword);

            if ($needPersist) {
                $entityManager->persist($user);
            }

            $entityManager->flush();
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
