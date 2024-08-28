<?php

namespace App\Service;

use App\Repository\CarModelRepository;
use Doctrine\ORM\EntityManagerInterface;

class CarModelService
{
    private EntityManagerInterface $entityManager;
    private CarModelRepository $carModelRepository;

    /**
     * @param EntityManagerInterface $entityManager
     * @param CarModelRepository $carModelRepository
     */
    public function __construct(EntityManagerInterface $entityManager, CarModelRepository $carModelRepository)
    {
        $this->entityManager = $entityManager;
        $this->carModelRepository = $carModelRepository;
    }

    public function getCarModels(): array
    {
        $carModels = $this->carModelRepository->findAllNotDeleted();

        return array(
            'status' => true,
            'statusCode' => 200,
            'message' => "",
            'data' => $carModels
        );
    }

    /**
     * @param $carModel
     * @return array
     */
    public function remove($carModel): array
    {
        $result = $this->carModelRepository->remove($carModel);

        if (!$result) {
            return array(
                'status' => 400,
                'statusCode' => false,
                'message' => 'El modelo no se ha podido eliminar.',
                'data' => null
            );
        }

        return array(
            'status' => 200,
            'statusCode' => true,
            'message' => 'El modelo se ha eliminado correctamente.',
            'data' => null
        );
    }
}