<?php

namespace App\BLL;


use DateTime;
use App\BLL\BaseBLL;
use App\Entity\User;
use App\Entity\Imagen;
use App\Entity\Categoria;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Bundle\SecurityBundle\Security; // <--- 1. NUEVO: Importar Security

class ImagenBLL extends BaseBLL
{
    public function setRequestStack(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }
    public function setSecurity(Security $security)
    {
        $this->security = $security;
    }
    public function getImagenesConOrdenacion(?string $ordenacion)
    {
        if (!is_null($ordenacion)) { // Cuando se establece un tipo de ordenación específico
            $tipoOrdenacion = 'asc'; // Por defecto si no se había guardado antes en la variable de sesión
            $session = $this->requestStack->getSession(); // Abrir la sesión
            $imagenesOrdenacion = $session->get('imagenesOrdenacion');

            if (!is_null($imagenesOrdenacion)) { // Comprobamos si ya se había establecido un orden
                if ($imagenesOrdenacion['ordenacion'] === $ordenacion) // Por si se ha cambiado de campo a ordenar
                {
                    if ($imagenesOrdenacion['tipoOrdenacion'] === 'asc')
                        $tipoOrdenacion = 'desc';
                }
            }
            $session->set('imagenesOrdenacion', [ // Se guarda la ordenación actual
                'ordenacion' => $ordenacion,
                'tipoOrdenacion' => $tipoOrdenacion
            ]);
        } else { // La primera vez que se entra se establece por defecto la ordenación por id ascendente
            $ordenacion = 'id';
            $tipoOrdenacion = 'asc';
        }

        // 4. NUEVO: Obtenemos el usuario logueado
        $usuarioLogueado = $this->security->getUser();

        // 5. NUEVO: Se lo pasamos al repositorio como tercer argumento
        return $this->imagenRepository->findImagenesConCategoria(
            $ordenacion,
            $tipoOrdenacion,
            $usuarioLogueado
        );
    }
    public function nueva(array $data)
    {
        $imagen = new Imagen();
        $imagen->setNombre($data['nombre']);
        $imagen->setDescripcion($data['descripcion']);
        $imagen->setNumVisualizaciones($data['numVisualizaciones']);
        $imagen->setNumLikes($data['numLikes']);
        $imagen->setNumDownloads($data['numDownloads']);
        // El id de la categoria, la tenemos que busar en su BBDD
        $categoria = $this->em->getRepository(Categoria::class)->find($data['categoria']);
        $imagen->setCategoria($categoria);
        $fecha = DateTime::createFromFormat('d/m/Y', $data['fecha']);
        $imagen->setFecha($fecha);
        $usuario = $this->em->getRepository(User::class)->find($data['usuario']);
        $imagen->setUsuario($usuario);
        return $this->guardaValidando($imagen);
    }
}
