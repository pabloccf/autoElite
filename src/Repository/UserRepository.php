<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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

    public function getCurrentPassword($id)
    {
        $parameters = array();

        $query =
            'SELECT 
                u.password
            FROM 
                users u
            WHERE 
                u.id = :id'
        ;

        $parameters['id'] = $id;

        try {
            $query = $this->getEntityManager()->getConnection()->prepare($query);
            $query->executeQuery($parameters);
            $currentPassword = $query->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\Throwable $th) {
            throw $th;
        }

        return $currentPassword;
    }

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
