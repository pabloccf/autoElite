<?php

namespace App\Controller;

use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{

    private UserService $userService;
    private EntityManagerInterface $entityManager;

    /**
     * @param UserService $userService
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(UserService $userService, EntityManagerInterface $entityManager)
    {
        $this->userService = $userService;
        $this->entityManager = $entityManager;
    }

    #[Route('/user', name: 'list_user')]
    public function index(): Response
    {
        $result = $this->userService->getUsers();

        return $this->render('user/index.html.twig', array('users' => $result['data']));
    }
}
