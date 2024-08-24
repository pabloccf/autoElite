<?php

namespace App\Service;

use App\Repository\CarBrandRepository;
use Doctrine\ORM\EntityManagerInterface;

class CarBrandService
{
    private CarBrandRepository $carBrandRepository;
    private EntityManagerInterface $entityManager;

    /**
     * @param CarBrandRepository $carBrandRepository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(CarBrandRepository $carBrandRepository, EntityManagerInterface $entityManager)
    {
        $this->carBrandRepository = $carBrandRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * Función que imprime todas las marcas de los coches de la base de datos
     *
     * @author Pablo López Gosálvez <i92logop@uco.es>
     *
     * @return array
     */
    public function getCarBrands(): array
    {
        $carBrands = $this->carBrandRepository->findAll();

        return array(
            'status' => true,
            'statusCode' => 200,
            'message' => "",
            'data' => $carBrands
        );
    }
}