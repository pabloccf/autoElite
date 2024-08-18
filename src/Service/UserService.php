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

    public function getUsers() {
        $users = $this->userRepository->findAll();

        return array(
            'status' => true,
            'statusCode' => 200,
            'message' => "",
            'data' => $users
        );
    }

}