<?php

namespace App\Service;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class UserService
{
    private UserRepository $userRepository;
    private EntityManagerInterface $entityManager;

    /**
     * @param UserRepository $userRepository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(UserRepository $userRepository, EntityManagerInterface $entityManager)
    {
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
    }

    public function getUsers()
    {
        $users = $this->userRepository->findAll();

        return array(
            'status' => true,
            'statusCode' => 200,
            'message' => "",
            'data' => $users
        );
    }

    public function getCurrentPassword($id)
    {
        $result = $this->userRepository->getCurrentPassword($id);

        return array(
            'status' => true,
            'statusCode' => 200,
            'message' => "",
            'data' => $result
        );
    }

    public function update($user, $encodedPassword, $needPersist = false)
    {
        $result = $this->userRepository->update($user, $encodedPassword, $needPersist);

        if (!$result) {
            return array(
                'status' => false,
                'statusCode' => 400,
                'message' => 'No se ha podido actualizar los datos del usuario',
                'data' => null
            );
        }

        return array(
            'status' => true,
            'statusCode' => 200,
            'message' => 'Usuario actualizado',
            'data' => null
        );
    }
}