<?php

namespace App\Controller;

use App\Entity\CarModel;
use App\Form\CarModelFormType;
use App\Repository\CarModelRepository;
use App\Service\CarModelService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CarModelController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private CarModelRepository $carModelRepository;
    private CarModelService $carModelService;

    /**
     * @param EntityManagerInterface $entityManager
     * @param CarModelRepository $carModelRepository
     * @param CarModelService $carModelService
     */
    public function __construct(EntityManagerInterface $entityManager, CarModelRepository $carModelRepository, CarModelService $carModelService)
    {
        $this->entityManager = $entityManager;
        $this->carModelRepository = $carModelRepository;
        $this->carModelService = $carModelService;
    }


    #[Route('/car/model', name: 'list_car_model')]
    public function index(): Response
    {
        $result = $this->carModelService->getCarModels();

        return $this->render('car_model/index.html.twig', ['carModels' => $result['data']]);
    }

    #[Route('car/model/add', name: 'add_car_model')]
    public function add(Request $request): Response
    {
        $carModel = new CarModel();

        $form = $this->createForm(CarModelFormType::class, $carModel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                /*$carBrand = $form->get('carBrand')->getData();
                $carModel->setCarBrand($carBrand);*/

                $carModelName = $form->get('name')->getData();
                /*$carModel->setName($carModelName);

                $bodyType = $form->get('body_type')->getData();
                $carModel->setBodyType($bodyType);*/

                $imageFile = $form->get('image')->getData();
                if ($imageFile) {
                    $modelName = preg_replace('/[^a-zA-Z0-9-_\.]/', '_', $carModelName);
                    $newFileName = $modelName.'_'.uniqid().'.'.$imageFile->guessExtension();

                    $imageFile->move($this->getParameter('models_directory'), $newFileName);
                    $carModel->setImage($newFileName);
                }

                var_dump($carModel->getImage());
                $this->entityManager->persist($carModel);
                $this->entityManager->flush();

                $this->redirectToRoute('list_car_model');
            } catch (\Exception $e) {
                $this->redirectToRoute('add_car_model');
            }
        }

        return $this->render('car_model/add.html.twig', ['carModelForm' => $form->createView()]);
    }

    #[Route('car/model/delete/{id}', name: 'delete_car_model', methods: ['POST', 'DELETE'])]
    public function delete($id): RedirectResponse
    {
        $carModel = $this->carModelRepository->find($id);

        if (!$carModel) {
            throw $this->createNotFoundException('No se encontro el modelo con el id ' . $id);
        }

        /*$imagePath = $this->getParameter('models_directory') . '/' . $carModel->getImage();
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }*/

        $this->carModelService->remove($carModel);

        return $this->redirectToRoute('list_car_model');
    }
}
