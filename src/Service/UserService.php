<?php

namespace App\Service;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class UserService
{
    private UserRepository $userRepository;
    private EntityManagerInterface $entityManager;

    /**
     * @param UserRepository $userRepository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(UserRepository $userRepository, EntityManagerInterface $entityManager)
    {
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
    }


    /**
     * Función que recupera la contraseña del usuario
     *
     * @author Pablo López Gosálvez <i92logop@uco.es>
     *
     * @param $id
     * @return array
     * @throws \Throwable
     */
    public function getCurrentPassword($id): array
    {
        $result = $this->userRepository->getCurrentPassword($id);

        return array(
            'status' => true,
            'statusCode' => 200,
            'message' => "",
            'data' => $result
        );
    }

    /**
     * Función que actualiza la contraseña de un usuario
     *
     * @author Pablo López Gosálvez <i92logop@uco.es>
     *
     * @param $user
     * @param $encodedPassword
     * @param $needPersist
     * @return array
     */
    public function update($user, $encodedPassword, $needPersist = false): array
    {
        $result = $this->userRepository->update($user, $encodedPassword, $needPersist);

        if (!$result) {
            return array(
                'status' => false,
                'statusCode' => 400,
                'message' => 'No se ha podido actualizar los datos del usuario',
                'data' => null
            );
        }

        return array(
            'status' => true,
            'statusCode' => 200,
            'message' => 'Usuario actualizado',
            'data' => null
        );
    }

    /**
     * Función que elimina un usuario
     *
     * @author Pablo López Gosálvez <i92logop@uco.es>
     *
     * @param $user
     * @return array
     */
    public function remove($user): array
    {
        if ($user->getRoles() == 'ROLE_ADMIN') {
            return array(
                'status' => false,
                'statusCode' => 400,
                'message' => 'El usuario no se puede eliminar',
                'data' => null
            );
        }

        $result = $this->userRepository->remove($user);

        if (!$result) {
            return array(
                'status' => false,
                'statusCode' => 400,
                'message' => 'El usuario no se puede eliminar',
                'data' => null
            );
        }

        return array(
            'status' => true,
            'statusCode' => 200,
            'message' => 'El usuario se ha eliminado correctamente',
            'data' => null
        );
    }
}