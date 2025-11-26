<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DefaultController extends AbstractController
{
    public function index()
    {
        $nombre = 'María';
        $saludo = 'Buenos días';
        return $this->render('prueba.html.twig', [
            'nombre' => $nombre,
            'saludo' => $saludo
        ]);
    }
}
