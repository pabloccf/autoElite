<?php

namespace App\Controller;

use App\Entity\Car;
use App\Entity\CarModel;
use App\Form\CarFormType;
use App\Repository\CarRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CarController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private CarRepository $carRepository;

    /**
     * @param EntityManagerInterface $entityManager
     * @param CarRepository $carRepository
     */
    public function __construct(EntityManagerInterface $entityManager, CarRepository $carRepository)
    {
        $this->entityManager = $entityManager;
        $this->carRepository = $carRepository;
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
}
