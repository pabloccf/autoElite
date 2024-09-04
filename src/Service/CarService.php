<?php

namespace App\Service;

use App\Repository\CarRepository;
use Doctrine\ORM\EntityManagerInterface;

class CarService
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


    /**
     * Función que elimina un coche
     *
     * @author Pablo López Gosálvez <i92logop@uco.es>
     *
     * @param $car
     * @return array
     */
    public function remove($car): array
    {
        $result = $this->carRepository->remove($car);

        if (!$result) {
            return array(
                'code' => 400,
                'statusCode' => false,
                'message' => 'El coche no se ha podido eliminar.',
                'data' => null
            );
        }

        return array(
            'code' => 200,
            'statusCode' => true,
            'message' => 'El coche se ha eliminado correctamente.',
            'data' => null
        );
    }
}