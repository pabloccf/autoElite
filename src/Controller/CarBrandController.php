<?php

namespace App\Controller;

use App\Entity\CarBrand;
use App\Form\CarBrandFormType;
use App\Repository\CarBrandRepository;
use App\Service\CarBrandService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CarBrandController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private CarBrandService $carBrandService;
    private CarBrandRepository $carBrandRepository;

    /**
     * @param EntityManagerInterface $entityManager
     * @param CarBrandService $carBrandService
     */
    public function __construct(EntityManagerInterface $entityManager, CarBrandService $carBrandService, CarBrandRepository $carBrandRepository)
    {
        $this->entityManager = $entityManager;
        $this->carBrandService = $carBrandService;
        $this->carBrandRepository = $carBrandRepository;
    }


    /**
     * Función que renderiza la vista de la lista de marcas de coches
     *
     * @author Pablo López Gosálvez <i92logop@uco.es>
     *
     * @return Response
     */
    #[Route('/car/brand', name: 'list_car_brand')]
    public function index(): Response
    {
        $result = $this->carBrandService->getCarBrands();

        return $this->render('car_brand/index.html.twig', ['carBrands' => $result['data']]);
    }

    /**
     * Función que renderiza la vista de añadir una marca de coche
     *
     * @author Pablo López Gosálvez <i92logop@uco.es>
     *
     * @param Request $request
     * @return Response
     */
    #[Route('car/brand/add', name: 'add_car_brand')]
    public function add(Request $request): Response
    {
        $carBrand = new CarBrand();

        $form = $this->createForm(CarBrandFormType::class, $carBrand);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $carBrandName = $form->get('name')->getData();
                $carBrand->setName($carBrandName);

                $logoFile = $form->get('logo')->getData();
                if ($logoFile) {
                    $brandName = preg_replace('/[^a-zA-Z0-9-_\.]/', '_', $carBrandName);
                    $newFileName = $brandName.'.'.$logoFile->guessExtension();

                    $logoFile->move($this->getParameter('logos_directory'), $newFileName);
                    $carBrand->setLogo($newFileName);
                }

                $this->entityManager->persist($carBrand);
                $this->entityManager->flush();

                return $this->redirectToRoute('list_car_brand');
            } catch (\Exception $e) {
                return $this->redirectToRoute('add_car_brand');
            }
        }

        return $this->render('car_brand/add.html.twig', ['carBrandForm' => $form->createView()]);
    }

    /**
     * Función que elimina una marca de coche
     *
     * @author Pablo López Gosálvez <i92logop@uco.es>
     *
     * @param $id
     * @return RedirectResponse
     */
    #[Route('car/brand/delete/{id}', name: 'delete_car_brand', methods: ['POST', 'DELETE'])]
    public function delete($id): RedirectResponse
    {
        $carBrand = $this->carBrandRepository->find($id);

        if (!$carBrand) {
            throw $this->createNotFoundException('No se encontró la marca con el id ' . $id);
        }

        $logoPath = $this->getParameter('logos_directory') . '/' . $carBrand->getLogo();
        if (file_exists($logoPath)) {
            unlink($logoPath);
        }

        $this->carBrandService->remove($carBrand);

        return $this->redirectToRoute('list_car_brand');
    }
}
