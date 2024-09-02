<?php

namespace App\Service;

use App\Repository\CarBrandRepository;
use Doctrine\DBAL\Exception;
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
     * Función que edita los datos de una marca de coche
     *
     * @author Pablo López Gosálvez <i92logop@uco.es>
     *
     * @param $carBrand
     * @param $needPersist
     * @return array
     */
    public function update($carBrand, $needPersist = false): array
    {
        $result = $this->carBrandRepository->update($carBrand, $needPersist);

        if (!$result) {
            return array(
                'status' => false,
                'statusCode' => 400,
                'message' => 'No se ha podido actualizar los datos de la marca del coche.',
                'data' => null
            );
        }

        return array(
            'status' => true,
            'statusCode' => 200,
            'message' => 'La marca del coche se ha actualizado correctamente.',
            'data' => null
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