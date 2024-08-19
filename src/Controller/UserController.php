<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
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

    /**
     * @return Response
     */
    #[Route('/user', name: 'list_user')]
    public function index(): Response
    {
        $result = $this->userService->getUsers();

        return $this->render('user/index.html.twig', array('users' => $result['data']));
    }

    /**
     * @param $id
     * @return Response
     */
    #[Route('/view/{id}', name: 'view_user')]
    public function view($id): Response
    {
        $user = $this->userRepository->find($id);

        if (!$user) {
            throw $this->createNotFoundException('Usuario no encontrado');
        }

        return $this->render('user/view.html.twig', array('user' => $user));
    }

    /**
     * @param $id
     * @return Response
     */
    #[Route('/edit/{id}', name: 'edit_user')]
    public function edit($id): Response
    {
        $user = $this->userRepository->find($id);

        if (!$user) {
            throw $this->createNotFoundException('Usuario no encontrado');
        }

        $form = $this->createEditForm($user);

        return $this->render('user/edit.html.twig', array('user' => $user, 'form' => $form->createView()));
    }

    /**
     * @param User $user
     * @return FormInterface
     */
    private function createEditForm(User $user): \Symfony\Component\Form\FormInterface
    {
        return $this->createForm(RegistrationFormType::class, $user,
            array('action' => $this->generateUrl('update_user', array('id' => $user->getId())), 'method' => 'PUT'))
        ;
    }

    /**
     * @param $id
     * @param Request $request
     * @return RedirectResponse|Response
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    #[Route('/update/{id}', name: 'update_user')]
    public function update($id, Request $request): RedirectResponse|Response
    {
        $user = $this->userRepository->find($id);

        if (!$user) {
            throw $this->createNotFoundException('Usuario no encontrado');
        }

        $form = $this->createEditForm($user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $form->get('password')->getData();

            if (!empty($password)) {
                $encoder = $this->container->get('security.password_encoder');
                $encoded = $encoder->encodePassword($user, $password);
            } else {
                $result = $this->userService->getCurrentPassword($id);
                $encoded = $result['data'][0]['password'];
            }

            $this->userService->update($user, $encoded);

            return $this->redirectToRoute('list_user');
        }

        return $this->render('user/edit.html.twig', array('user' => $user, 'form' => $form->createView()));
    }
}
