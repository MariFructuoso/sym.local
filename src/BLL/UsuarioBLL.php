<?php

namespace App\BLL;

use App\Entity\User;

class UsuarioBLL extends BaseBLL
{
    public function nuevo(string $username, string $email, string $password)
    {
        $usuario = new User();
        $usuario->setUsername($username);
        $usuario->setEmail($email);
        // Usamos $this->encoder heredado de BaseBLL
        $usuario->setPassword($this->encoder->hashPassword($usuario, $password));
        $usuario->setRoles(['ROLE_USER']);
        
        return $this->guardaValidando($usuario);
    }

    // Tal cual pide el PDF
    public function toArray(User $usuario): array
    {
        if (is_null($usuario))
            throw new \Exception("No existe el usuario");
        
        if (!($usuario instanceof User))
            throw new \Exception("La entidad no es un User");

        return [
            'id' => $usuario->getId(),
            'username' => $usuario->getUsername(),
            'email' => $usuario->getEmail(),
            'roles' => $usuario->getRoles()
        ];
    }
}