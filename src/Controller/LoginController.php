<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
// 1. CORRECCIÓN: Tienes que importar esto para que el comentario @var funcione
use Symfony\Component\HttpFoundation\Session\Session; 

final class LoginController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function index(AuthenticationUtils $authenticationUtils, Request $request): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        //CODIGO CAMBIADO AL QUE DICE EN EL PDF PARA QUE GETFLASHBAG NO DE ERROR
        /** @var Session $session */ //<-----------------
        $session = $request->getSession();//<------------
       
        $verifyEmailError = $session->getFlashBag()->get('verify_email_error', []);
        $success = $session->getFlashBag()->get('success', []);

        return $this->render('login/index.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
            'verify_email_error' => $verifyEmailError[0] ?? null,
            'success' => $success[0] ?? null,
        ]);
    }

    #[Route('/logout', name: 'app_logout', methods: ['GET'])]
    public function logout(): void
    {
        throw new \Exception('Don\'t forget to activate logout in security.yaml');
    }
}