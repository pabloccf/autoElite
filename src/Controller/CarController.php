<?php

namespace App\Controller;

use App\Entity\Car;
use App\Form\CarFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CarController extends AbstractController
{
    #[Route('/car', name: 'list_car')]
    public function index(): Response
    {
        return $this->render('car/index.html.twig', [
            'controller_name' => 'CarController',
        ]);
    }

    #[Route('/car/add', name: 'add_car')]
    public function add(): Response
    {
        $car = new Car();
        $form = $this->createForm(CarFormType::class, $car);

        return $this->render('car/add.html.twig', ['carForm' => $form->createView()]);
    }
}
