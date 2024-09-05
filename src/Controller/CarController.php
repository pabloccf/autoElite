<?php

namespace App\Controller;

use App\Entity\Car;
use App\Entity\CarModel;
use App\Form\CarFormType;
use App\Repository\CarRepository;
use App\Service\CarService;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CarController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private CarRepository $carRepository;
    private CarService $carService;

    /**
     * @param EntityManagerInterface $entityManager
     * @param CarRepository $carRepository
     * @param CarService $carService
     */
    public function __construct(EntityManagerInterface $entityManager, CarRepository $carRepository, CarService $carService)
    {
        $this->entityManager = $entityManager;
        $this->carRepository = $carRepository;
        $this->carService = $carService;
    }


    #[Route('/car', name: 'list_car')]
    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        // Verificar si el usuario está autenticado
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');  // Redirigir a login si no está autenticado
        }

        $query = $this->carRepository->findNotDeleted();

        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            8 /*limit per page*/
        );

        return $this->render('car/index.html.twig', ['cars' => $pagination]);
    }

    #[Route('/car/add', name: 'add_car')]
    public function add(Request $request): Response
    {
        // Verificar si el usuario está autenticado
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');  // Redirigir a login si no está autenticado
        }

        $car = new Car();
        $form = $this->createForm(CarFormType::class, $car);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $carModel = $form->get('carModel')->getData();
            $car->setCarModel($carModel);

            $this->entityManager->persist($car);
            $this->entityManager->flush();

            return $this->redirectToRoute('list_car');
        }

        return $this->render('car/add.html.twig', ['carForm' => $form->createView()]);
    }

    #[Route('car/view/{id}', name: 'view_car')]
    public function view($id): RedirectResponse|Response
    {
        // Verificar si el usuario está autenticado
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');  // Redirigir a login si no está autenticado
        }

        $car = $this->carRepository->find($id);

        if (!$car) {
            throw $this->createNotFoundException('No se encontro el coche con el id ' . $id);
        }

        $carModel = $car->getCarModel();
        $carBrand = $carModel->getCarBrand();

        return $this->render('car/view.html.twig', array('car' => $car, 'carModel' => $carModel, 'carBrand' => $carBrand));
    }

    /**
     * Función que renderiza la vista de editar los datos de un coche
     *
     * @author Pablo López Gosálvez <i92logop@uco.es>
     *
     * @param $id
     * @param Request $request
     * @return RedirectResponse|Response
     */
    #[Route('car/edit/{id}', name: 'edit_car')]
    public function edit($id, Request $request): RedirectResponse|Response
    {
        // Verificar si el usuario está autenticado
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');  // Redirigir a login si no está autenticado
        }

        $car = $this->carRepository->find($id);

        if (!$car) {
            throw $this->createNotFoundException('No se encontro el coche con el id ' . $id);
        }

        $form = $this->createForm(CarFormType::class, $car, ['isEdit' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->carService->update($car);

           return $this->redirectToRoute('list_car');
        }

        return $this->render('car/edit.html.twig', array('car' => $car, 'carForm' => $form->createView()));
    }

    /**
     * Función que se encarga de eliminar un coche
     *
     * @author Pablo López Gosálvez <i92logop@uco.es>
     *
     * @param $id
     * @return RedirectResponse
     */
    #[Route('car/delete/{id}', name: 'delete_car', methods: ['POST', 'DELETE'])]
    public function delete($id): RedirectResponse
    {
        // Verificar si el usuario está autenticado
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');  // Redirigir a login si no está autenticado
        }

        $car = $this->carRepository->find($id);

        if (!$car) {
            throw $this->createNotFoundException('No se encontro el coche con el id ' . $id);
        }

        $this->carService->remove($car);

        return $this->redirectToRoute('list_car');
    }
}
