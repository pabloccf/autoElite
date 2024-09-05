<?php

namespace App\Controller;

use App\Entity\CarBrand;
use App\Form\CarBrandFormType;
use App\Repository\CarBrandRepository;
use App\Service\CarBrandService;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
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
     * @param CarBrandRepository $carBrandRepository
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
    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        // Verificar si el usuario está autenticado
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');  // Redirigir a login si no está autenticado
        }

        $query = $this->carBrandRepository->findAllNotDeleted();

        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            8 /*limit per page*/
        );

        return $this->render('car_brand/index.html.twig', ['carBrands' => $pagination]);
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
        // Verificar si el usuario está autenticado
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');  // Redirigir a login si no está autenticado
        }

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
     * Función que renderiza la vista de editar los datos de una marca de coche
     *
     * @author Pablo López Gosálvez <i92logop@uco.es>
     *
     * @param $id
     * @param Request $request
     * @return RedirectResponse|Response
     */
    #[Route('car/brand/edit/{id}', name: 'edit_car_brand')]
    public function edit($id, Request $request): RedirectResponse|Response
   {
       // Verificar si el usuario está autenticado
       if (!$this->getUser()) {
           return $this->redirectToRoute('app_login');  // Redirigir a login si no está autenticado
       }

        $carBrand = $this->carBrandRepository->find($id);

        if (!$carBrand) {
            throw $this->createNotFoundException('No se encontró la marca con el id ' . $id);
        }

        $form = $this->createForm(CarBrandFormType::class, $carBrand, ['isEdit' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $carBrandName = $form->get('name')->getData();

            $logoFile = $form->get('logo')->getData();
            if ($logoFile) {
                $brandName = preg_replace('/[^a-zA-Z0-9-_\.]/', '_', $carBrandName);
                $newFileName = $brandName.'.'.$logoFile->guessExtension();

                $logoFile->move($this->getParameter('logos_directory'), $newFileName);
                $carBrand->setLogo($newFileName);
            }

            $this->carBrandService->update($carBrand);

            return $this->redirectToRoute('list_car_brand');
        }

        return $this->render('car_brand/edit.html.twig', array('carBrand' => $carBrand, 'carBrandForm' => $form->createView()));
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
        // Verificar si el usuario está autenticado
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');  // Redirigir a login si no está autenticado
        }

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
