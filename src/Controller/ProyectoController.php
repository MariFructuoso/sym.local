<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Imagen;
use Symfony\Component\Routing\Annotation\Route;


class ProyectoController extends AbstractController
{
    #[Route('/', name: 'sym_index')]
    public function index()
    {
        $imagenesHome[] = new Imagen('1.jpg', 'descripción imagen 1', 1, 46, 61, 135);
        $imagenesHome[] = new Imagen('2.jpg', 'descripción imagen 2', 1, 46, 61, 135);
        $imagenesHome[] = new Imagen('3.jpg', 'descripción imagen 3', 1, 46, 61, 135);
        $imagenesHome[] = new Imagen('4.jpg', 'descripción imagen 4', 1, 46, 61, 135);
        $imagenesHome[] = new Imagen('5.jpg', 'descripción imagen 5', 1, 46, 61, 135);
        $imagenesHome[] = new Imagen('6.jpg', 'descripción imagen 6', 1, 46, 61, 135);
        $imagenesHome[] = new Imagen('7.jpg', 'descripción imagen 7', 1, 46, 61, 135);
        $imagenesHome[] = new Imagen('8.jpg', 'descripción imagen 8', 1, 46, 61, 135);
        $imagenesHome[] = new Imagen('9.jpg', 'descripción imagen 9', 1, 46, 61, 135);
        $imagenesHome[] = new Imagen('10.jpg', 'descripción imagen 10', 1, 46, 61, 135);
        $imagenesHome[] = new Imagen('11.jpg', 'descripción imagen 11', 1, 46, 61, 135);
        $imagenesHome[] = new Imagen('12.jpg', 'descripción imagen 12', 1, 456, 610, 130);

        return $this->render('index.html.twig', [
            'imagenes' => $imagenesHome
        ]);
    }
}
