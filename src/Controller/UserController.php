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

        $deleteFormAjax = $this->createCustomForm(':USER_ID', 'DELETE', 'delete_user');

        return $this->render('user/index.html.twig', array('users' => $result['data'], 'delete_form_ajax' => $deleteFormAjax->createView()));
    }

    /**
     * Función que renderiza la vista de editar un usuario
     *
     * @author Pablo López Gosálvez <i92logop@uco.es>
     *
     * @param $id
     * @return Response
     */
    #[Route('/user/edit/{id}', name: 'edit_user')]
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
     * Función que crea el formulario de editar un usuario
     *
     * @author Pablo López Gosálvez <i92logop@uco.es>
     *
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
     * Función que procesa y edita un usuario
     *
     * @author Pablo López Gosálvez <i92logop@uco.es>
     *
     * @param $id
     * @param Request $request
     * @return RedirectResponse|Response
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    #[Route('/user/update/{id}', name: 'update_user', methods: ['POST', 'PUT'])]
    public function update($id, Request $request): RedirectResponse|Response
    {
        $user = $this->userRepository->find($id);

        if (!$user) {
            throw $this->createNotFoundException('Usuario no encontrado');
        }

        $form = $this->createEditForm($user);
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
    public function delete(Request $request, $id)
    {
        $user = $this->userRepository->find($id);

        if (!$user) {
            if ($request->isXmlHttpRequest()) {
                return new JsonResponse(['status' => false, 'statusCode' => 404, 'message' => 'Usuario no encontrado'], 404);
            }
            return $this->redirectToRoute('list_user');
        }

        $form = $this->createCustomForm($user->getId(), 'DELETE', 'delete_user');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($request->isXmlHttpRequest()) {
                $result = $this->userService->remove($user);

                return new JsonResponse($result, $result['statusCode'], array('Content-Type' => 'application/json'));
            }

            return $this->redirectToRoute('list_user');
        }
    }

    /**
     * Función que crea un formulario
     *
     * @author Pablo López Gosálvez <i92logop@uco.es>
     *
     * @param $id
     * @param $method
     * @param $route
     * @return FormInterface
     */
    private function createCustomForm($id, $method, $route)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl($route, array('id' => $id)))
            ->setMethod($method)
            ->getForm();
    }
}
