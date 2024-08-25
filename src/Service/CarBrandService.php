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

    /**
     * Función que elimina una marca de coche
     *
     * @author Pablo López Gosálvez <i92logop@uco.es>
     *
     * @param $carBrand
     * @return array
     */
    public function remove($carBrand): array
    {
        $result = $this->carBrandRepository->remove($carBrand);

        if (!$result) {
            return array(
                'status' => 400,
                'statusCode' => false,
                'message' => 'La marca no se puede eliminar',
                'data' => null
            );
        } else {
            return array(
                'status' => 200,
                'statusCode' => true,
                'message' => 'La marca se ha eliminado correctamente',
                'data' => null
            );
        }
    }
}