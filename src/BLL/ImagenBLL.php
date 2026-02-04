<?php

namespace App\BLL;

use DateTime;
use App\Entity\User;
use App\Entity\Imagen;
use App\Entity\Categoria;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Bundle\SecurityBundle\Security;

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
        if (!is_null($ordenacion)) {
            $tipoOrdenacion = 'asc';
            $session = $this->requestStack->getSession();
            $imagenesOrdenacion = $session->get('imagenesOrdenacion');

            if (!is_null($imagenesOrdenacion)) {
                if ($imagenesOrdenacion['ordenacion'] === $ordenacion) {
                    if ($imagenesOrdenacion['tipoOrdenacion'] === 'asc')
                        $tipoOrdenacion = 'desc';
                }
            }
            $session->set('imagenesOrdenacion', [
                'ordenacion' => $ordenacion,
                'tipoOrdenacion' => $tipoOrdenacion
            ]);
        } else {
            $ordenacion = 'id';
            $tipoOrdenacion = 'asc';
        }

        $usuarioLogueado = $this->security->getUser();

        return $this->imagenRepository->findImagenesConCategoria(
            $ordenacion,
            $tipoOrdenacion,
            $usuarioLogueado
        );
    }

    public function getImagenes(?string $order, ?string $descripcion, ?string $fechaInicial, ?string $fechaFinal)
    {
        $imagenes = $this->em->getRepository(Imagen::class)->findImagenes(
            $order, 
            $descripcion, 
            $fechaInicial, 
            $fechaFinal, 
            $usuario = null // Pasamos null explícitamente como indica el ejercicio
        );
        
        return $this->entitiesToArray($imagenes);
    }

    public function actualizaImagen(Imagen $imagen, array $data)
    {
        $imagen->setNombre($data['nombre']);
        $imagen->setDescripcion($data['descripcion']);
        $imagen->setNumVisualizaciones($data['numVisualizaciones']);
        $imagen->setNumLikes($data['numLikes']);
        $imagen->setNumDownloads($data['numDownloads']);

        // El id de la categoria
        $categoria = $this->em->getRepository(Categoria::class)->find($data['categoria']);
        $imagen->setCategoria($categoria);

        $fecha = DateTime::createFromFormat('d/m/Y', $data['fecha']);
        $imagen->setFecha($fecha);

        $usuario = $this->em->getRepository(User::class)->find($data['usuario']);
        $imagen->setUsuario($usuario);

        return $this->guardaValidando($imagen);
    }

    // Esta es la ÚNICA versión de nueva que debe haber (refactorizada)
    public function nueva(array $data)
    {
        $imagen = new Imagen();
        return $this->actualizaImagen($imagen, $data);
    }
}
