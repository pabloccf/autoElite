<?php

namespace App\Controller;

use App\Entity\CarModel;
use App\Form\CarModelFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CarModelController extends AbstractController
{
    #[Route('/car/model', name: 'list_car_model')]
    public function index(): Response
    {
        return $this->render('car_model/index.html.twig', [
            'controller_name' => 'CarModelController',
        ]);
    }

    #[Route('car/model/add', name: 'add_car_model')]
    public function add(): Response
    {
        $carModel = new CarModel();

        $form = $this->createForm(CarModelFormType::class, $carModel);

        return $this->render('car_model/add.html.twig', ['carModelForm' => $form->createView()]);
    }
}
