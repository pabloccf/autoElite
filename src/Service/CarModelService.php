<?php

namespace App\Service;

use App\Repository\CarModelRepository;
use Doctrine\DBAL\Exception;
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

    /**
     * Función que imprime todos los modelos de los coches de la base de datos
     *
     * @author Pablo López Gosálvez <i92logop@uco.es>
     *
     * @return array
     * @throws Exception
     * @throws \Throwable
     */
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
     * Función que edita los datos de un modelo de un coche
     *
     * @author Pablo López Gosálvez <i92logop@uco.es>
     *
     * @param $carModel
     * @param $needPersist
     * @return array
     */
    public function update($carModel, $needPersist = false): array
    {
        $result = $this->carModelRepository->update($carModel, $needPersist);

        if (!$result) {
            return array(
                'status' => 400,
                'statusCode' => false,
                'message' => 'No se han podido actualizar los datos del modelo del coche.',
                'data' => null
            );
        }

        return array(
            'status' => 200,
            'statusCode' => true,
            'message' => 'El modelo del coche se ha actualizado correctamente.',
            'data' => null
        );
    }

    /**
     * Función que elimina un modelo de un coche
     *
     * @author Pablo López Gosálvez <i92logop@uco.es>
     *
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