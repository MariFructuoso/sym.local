<?php

namespace App\Controller;

use App\Entity\Imagen;
use App\Form\ImagenType;
use App\Repository\ImagenRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\BLL\ImagenBLL;

#[Route('/imagen')]
final class ImagenController extends AbstractController
{
    #[Route('/', name: 'app_imagen_index', methods: ['GET'])]
    #[Route('/orden/{ordenacion}', name: 'app_imagen_index_ordenado', methods: ['GET'])]
    public function index(
        ImagenBLL $imagenBLL,
        string $ordenacion = null
    ): Response {
        // La BLL ya se encarga de obtener el usuario logueado y filtrar
        $imagens = $imagenBLL->getImagenesConOrdenacion($ordenacion);

        return $this->render('imagen/index.html.twig', [
            'imagens' => $imagens
        ]);
    }

    #[Route('/busqueda', name: 'app_imagen_index_busqueda', methods: ['POST'])]
    public function busqueda(Request $request, ImagenRepository $imagenRepository): Response
    {
        $busqueda = $request->request->get('busqueda');
        $fechaInicial = $request->request->get('fechaInicial');
        $fechaFinal = $request->request->get('fechaFinal');

        // --- CORRECCIÓN IMPRESCINDIBLE ---
        // Obtenemos el usuario para pasarlo al repositorio, ya que modificamos la función findImagenes
        $usuarioLogueado = $this->getUser();

        // Le pasamos el 4º argumento
        $imagenes = $imagenRepository->findImagenes($busqueda, $fechaInicial, $fechaFinal, $usuarioLogueado);

        return $this->render('imagen/index.html.twig', [
            'imagens' => $imagenes,
            'busqueda' => $busqueda,
            'fechaInicial' => $fechaInicial,
            'fechaFinal' => $fechaFinal
        ]);
    }

    #[Route('/new', name: 'app_imagen_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $imagen = new Imagen();
        $form = $this->createForm(ImagenType::class, $imagen);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $file */
            $file = $form['nombre']->getData();

            if ($file) {
                // 1. Generamos nombre único
                $fileName = md5(uniqid()) . '.' . $file->guessExtension();
                
                // 2. Movemos el archivo a la carpeta
                $file->move($this->getParameter('images_directory_subidas'), $fileName);
                
                // 3. Actualizamos la entidad
                $imagen->setNombre($fileName);

                // 4. Asignamos el usuario logueado (CLAVE DEL EJERCICIO 6.4)
                $usuario = $this->getUser();
                $imagen->setUsuario($usuario);

                // 5. Guardamos en Base de Datos
                $entityManager->persist($imagen);
                $entityManager->flush();

                $this->addFlash('mensaje', 'Se ha creado la imagen ' . $imagen->getNombre());

                return $this->redirectToRoute('app_imagen_index', [], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->render('imagen/new.html.twig', [
            'imagen' => $imagen,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_imagen_show', methods: ['GET'])]
    public function show(Imagen $imagen): Response
    {
        return $this->render('imagen/show.html.twig', [
            'imagen' => $imagen,
        ]);
    }

    #[Route('/edit/{id}', name: 'app_imagen_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Imagen $imagen, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ImagenType::class, $imagen);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $file */
            $file = $form->get('nombre')->getData();

            if ($file) {
                $fileName = md5(uniqid()) . '.' . $file->guessExtension();
                $file->move($this->getParameter('images_directory_subidas'), $fileName);
                $imagen->setNombre($fileName);
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_imagen_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('imagen/edit.html.twig', [
            'imagen' => $imagen,
            'form' => $form,
        ]);
    }

    #[Route('/delete/{id}', name: 'app_imagen_delete', methods: ['POST'])]
    public function delete(Request $request, Imagen $imagen, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $imagen->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($imagen);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_imagen_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/delete/json/{id}', name: 'app_imagen_delete_json', methods: ['DELETE'])]
    public function deleteJson(Imagen $imagen, ImagenRepository $imagenRepository): Response
    {
        $imagenRepository->remove($imagen, true);
        return new JsonResponse(['eliminado' => true]);
    }
}
