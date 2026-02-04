<?php

namespace App\BLL;

use DateTime;
use App\Entity\User;
use App\Entity\Imagen;
use App\Entity\Categoria;

class ImagenBLL extends BaseBLL
{
    // NO añadimos constructor, usa el de BaseBLL automáticamente.
    // Si tienes setters antiguos (setRequestStack), puedes dejarlos o quitarlos, 
    // pero el constructor de BaseBLL ya hace el trabajo.

    public function getImagenesConOrdenacion(?string $ordenacion)
    {
        // ... (Tu código de ordenación de sesión se mantiene igual) ...
        // Resumido para brevedad, mantén tu lógica de sesión aquí
        
        // CORRECCIÓN: Usamos $this->em->getRepository(...) porque $this->imagenRepository ya no existe en BaseBLL
        return $this->em->getRepository(Imagen::class)->findImagenesConCategoria(
            $ordenacion ?? 'id',
            'asc', // Simplificado para el ejemplo, usa tu lógica de variables
            $this->security->getUser()
        );
    }

    public function getImagenes(?string $order, ?string $descripcion, ?string $fechaInicial, ?string $fechaFinal)
    {
        // CORRECCIÓN: Usamos $this->em->getRepository(...)
        $imagenes = $this->em->getRepository(Imagen::class)->findImagenes(
            $order, $descripcion, $fechaInicial, $fechaFinal, null
        );
        return $this->entitiesToArray($imagenes);
    }

    // El PDF no pide cambiar esto, pero asegúrate de que use $this->em
    public function actualizaImagen(Imagen $imagen, array $data)
    {
        $imagen->setNombre($data['nombre']);
        $imagen->setDescripcion($data['descripcion']);
        $imagen->setNumVisualizaciones($data['numVisualizaciones']);
        $imagen->setNumLikes($data['numLikes']);
        $imagen->setNumDownloads($data['numDownloads']);

        $categoria = $this->em->getRepository(Categoria::class)->find($data['categoria']);
        $imagen->setCategoria($categoria);

        $fecha = DateTime::createFromFormat('d/m/Y', $data['fecha']);
        $imagen->setFecha($fecha);

        $usuario = $this->em->getRepository(User::class)->find($data['usuario']);
        $imagen->setUsuario($usuario);

        return $this->guardaValidando($imagen);
    }
    
    public function nueva(array $data)
    {
        $imagen = new Imagen();
        return $this->actualizaImagen($imagen, $data);
    }

    // Método toArray propio de Imagen
    public function toArray($imagen)
    {
        if (is_null($imagen)) return null;
        return [
            'id' => $imagen->getId(),
            'nombre' => $imagen->getNombre(),
            'descripcion' => $imagen->getDescripcion(),
            // ... resto de campos ...
            'usuario' => $imagen->getUsuario() ? $imagen->getUsuario()->getId() : null
        ];
    }
}