<?php

namespace App\Controller;

use App\Entity\Sale;
use App\Form\SaleFormType;
use App\Repository\CarRepository;
use App\Repository\SaleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SaleController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private SaleRepository $saleRepository;
    private CarRepository $carRepository;

    /**
     * @param EntityManagerInterface $entityManager
     * @param SaleRepository $saleRepository
     * @param CarRepository $carRepository
     */
    public function __construct(EntityManagerInterface $entityManager, SaleRepository $saleRepository, CarRepository $carRepository)
    {
        $this->entityManager = $entityManager;
        $this->saleRepository = $saleRepository;
        $this->carRepository = $carRepository;
    }


    #[Route('/sale', name: 'list_sale')]
    public function index(): Response
    {
        // Verificar si el usuario está autenticado
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');  // Redirigir a login si no está autenticado
        }

        return $this->render('sale/index.html.twig', [
            'controller_name' => 'SaleController',
        ]);
    }

    #[Route('/sale/add/{carId}', name: 'add_sale')]
    public function add(Request $request, Security $security, $carId): RedirectResponse|Response
    {
        // Verificar si el usuario está autenticado
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');  // Redirigir a login si no está autenticado
        }

        $user = $security->getUser();
        $car = $this->carRepository->find($carId);

        if (!$user || !$car) {
            throw $this->createNotFoundException('No se encontró el coche o el usuario');
        }

        $sale = new Sale();
        $sale->setSaleDate(new \DateTime('today'));
        $sale->setUser($user);
        $sale->setCar($car);

        $form = $this->createForm(SaleFormType::class, $sale);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($sale);
            $this->entityManager->flush();

            return $this->redirectToRoute('list_sale');
        }

        return $this->render('sale/add.html.twig', ['saleForm' => $form->createView()]);
    }
}
