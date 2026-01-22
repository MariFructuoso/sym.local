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

        // Usamos la función que acabamos de crear en el repositorio
        $imagenes = $imagenRepository->findImagenes($busqueda, $fechaInicial, $fechaFinal);

        return $this->render('imagen/index.html.twig', [
            'imagens' => $imagenes,
            // Pasamos los valores de vuelta para que se mantengan en los inputs
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
            // $file almacena el archivo subido
            /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
            $file = $form['nombre']->getData();

            // --- CORRECCIÓN: Comprobamos si hay archivo antes de procesarlo ---
            if ($file) {
                // Generamos un nombre único
                $fileName = md5(uniqid()) . '.' . $file->guessExtension();

                // Movemos el archivo al directorio
                $file->move($this->getParameter('images_directory_subidas'), $fileName);

                // Actualizamos el nombre en la entidad
                $imagen->setNombre($fileName);

                // Solo persistimos si todo ha ido bien
                $entityManager->persist($imagen);
                $entityManager->flush();

                $this->addFlash('mensaje', 'Se ha creado la imagen ' . $imagen->getNombre());

                return $this->redirectToRoute('app_imagen_index', [], Response::HTTP_SEE_OTHER);
            } else {
                // Opcional: Si la imagen es obligatoria, puedes añadir un error al formulario aquí
                // $form->get('nombre')->addError(new FormError('Debes subir una imagen'));
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

    #[Route('/{id}/edit', name: 'app_imagen_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Imagen $imagen, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ImagenType::class, $imagen);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // BUSCAMOS EL ARCHIVO MANUALMENTE
            // Como 'mapped' es false, el archivo no está en $imagen, está en el form.
            /** @var UploadedFile $file */
            $file = $form->get('nombre')->getData();

            // Si el usuario subió una foto nueva, la procesamos
            if ($file) {
                $fileName = md5(uniqid()) . '.' . $file->guessExtension();

                // Movemos el archivo
                $file->move($this->getParameter('images_directory_subidas'), $fileName);

                // Actualizamos el nombre en la entidad manualmente
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

    #[Route('/{id}', name: 'app_imagen_delete', methods: ['POST'])]
    public function delete(Request $request, Imagen $imagen, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $imagen->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($imagen);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_imagen_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}', name: 'app_imagen_delete_json', methods: ['DELETE'])]
    public function deleteJson(Imagen $imagen, ImagenRepository $imagenRepository): Response
    {
        $imagenRepository->remove($imagen, true);
        return new JsonResponse(['eliminado' => true]);
    }
}
