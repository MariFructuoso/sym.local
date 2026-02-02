<?php

namespace App\Controller\API;

use App\BLL\ImagenBLL;
use App\Entity\Imagen;
use App\Controller\API\BaseApiController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route('/api')]
class ImagenApiController extends BaseApiController
{
    #[Route('/prueba', name: 'api_prueba', methods: ["GET"])]
    public function pruebaApi(): JsonResponse
    {
        return $this->json([
            'message' => 'Bienvenido al nuevo controlador!',
        ]);
    }
    #[Route('/imagenesapinueva', name: 'api_post_imagenes', methods: ['POST'])]
    public function post(Request $request, ImagenBLL $imagenBLL)
    {
        $data = $this->getContent($request);
        $imagen = $imagenBLL->nueva($data);
        return $this->getResponse($imagen, Response::HTTP_CREATED);
    }
    #[Route('/imagenesapi/{id}', name: 'api_get_imagen', requirements: ['id'=>'\d+'], methods: ['GET'])]
    public function getOne(Imagen $imagen, ImagenBLL $imagenBLL)
    {
        return $this->getResponse($imagenBLL->toArray($imagen));
    }

    #[Route('/imagenesapi', name: 'api_get_imagenes', methods: ['GET'])]
    public function getAll(Request $request, ImagenBLL $imagenBLL)
    {
        $imagenes = $imagenBLL->getImagenes();
        return $this->getResponse($imagenes);
    }

    #[Route('/imagenesapi/{id}', name: 'api_delete_imagen', requirements: ['id'=>'\d+'], methods: ['DELETE'])]
    public function delete(Imagen $imagen, ImagenBLL $imagenBLL)
    {
        $imagenBLL->delete($imagen);
        return $this->getResponse(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('/imagenesapi/{id}', name: 'api_update_imagen', requirements: ['id'=>'\d+'], methods: ['PUT'])]
    public function update(Request $request, Imagen $imagen, ImagenBLL $imagenBLL)
    {
        $data = $this->getContent($request);
        $imagen = $imagenBLL->actualizaImagen($imagen, $data);
        return $this->getResponse($imagen, Response::HTTP_OK);
    }
}
