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
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;

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
     * Función que renderiza la vista de la lista de usuarios
     *
     * @author Pablo López Gosálvez <i92logop@uco.es>
     *
     * @return Response
     */
    #[Route('/user', name: 'list_user')]
    public function index(): Response
    {
        $result = $this->userService->getUsers();

        return $this->render('user/index.html.twig', array('users' => $result['data']));
    }


    /**
     * Función que renderiza la vista de editar un usuario
     *
     *@author Pablo López Gosálvez <i92logop@uco.es>
     *
     * @param $id
     * @param Request $request
     * @return Response
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws \Throwable
     */
    #[Route('/user/edit/{id}', name: 'edit_user')]
    public function edit($id, Request $request): Response
    {
        $user = $this->userRepository->find($id);

        if (!$user) {
            throw $this->createNotFoundException('No se encontró el usuario con el id ' . $id);
        }

        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $form->get('plainPassword')->getData();

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

    /**
     * Función que elimina un usuario
     *
     * @author Pablo López Gosálvez <i92logop@uco.es>
     * @param Request $request
     * @param $id
     * @return JsonResponse|RedirectResponse|void
     */
    #[Route('/user/delete/{id}', name: 'delete_user', methods: ['POST', 'DELETE'])]
    public function delete($id)
    {
        $user = $this->userRepository->find($id);

        if (!$user) {
            throw $this->createNotFoundException('No se encontró el usuario con el id ' . $id);
        }

        $this->userService->remove($user);

        return $this->redirectToRoute('list_user');
    }
}
