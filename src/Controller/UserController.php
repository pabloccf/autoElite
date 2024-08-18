<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{

    private UserService $userService;
    private EntityManagerInterface $entityManager;
    private UserRepository $userRepository;

    /**
     * @param UserService $userService
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(UserService $userService, EntityManagerInterface $entityManager, UserRepository $userRepository)
    {
        $this->userService = $userService;
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
    }

    #[Route('/user', name: 'list_user')]
    public function index(): Response
    {
        $result = $this->userService->getUsers();

        return $this->render('user/index.html.twig', array('users' => $result['data']));
    }

    #[Route('/view/{id}', name: 'view_user')]
    public function view($id): Response
    {
        $user = $this->userRepository->find($id);

        if (!$user) {
            throw $this->createNotFoundException('Usuario no encontrado');
        }

        return $this->render('user/view.html.twig', array('user' => $user));
    }
}
