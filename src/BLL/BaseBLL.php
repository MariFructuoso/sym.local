<?php

namespace App\BLL;

use App\Entity\Imagen;
use App\Repository\ImagenRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

abstract class BaseBLL
{
    protected EntityManagerInterface $em;
    protected ValidatorInterface $validator;
    protected RequestStack $requestStack;
    protected Security $security;
    protected ImagenRepository $imagenRepository;
    public function __construct(
        EntityManagerInterface $em,
        ValidatorInterface $validator,
        RequestStack $requestStack,
        Security $security,
        ImagenRepository $imagenRepository
    ) {
        $this->em = $em;
        $this->validator = $validator;
        $this->requestStack = $requestStack;
        $this->security = $security;
        $this->imagenRepository = $imagenRepository;
    }


    public function toArray(Imagen $imagen)
    {
        if (is_null($imagen))
            return null;
        return [
            'id' => $imagen->getId(),
            'nombre' => $imagen->getNombre(),
            'descripcion' => $imagen->getDescripcion(),
            'categoria' => $imagen->getCategoria()->getNombre(),
            'numLikes' => $imagen->getNumLikes(),
            'numVisualizaciones' => $imagen->getNumVisualizaciones(),
            'numDownloads' => $imagen->getNumDownloads(),
            'fecha' => is_null($imagen->getFecha()) ? '' : $imagen->getFecha()->format('d/m/Y'),
            'usuario' => $imagen->getUsuario()->getId()
        ];
    }

    private function validate($entity)
    {
        $errors = $this->validator->validate($entity);
        if (count($errors) > 0) {
            $strError = '';
            foreach ($errors as $error) {
                if (!empty($strError))
                    $strError .= '\n';
                $strError .= $error->getMessage();
            }
            throw new BadRequestHttpException($strError);
        }
    }

    protected function guardaValidando($entity): array
    {
        $this->validate($entity); // Validación de los datos
        $this->em->persist($entity); // Se guardan los datos
        $this->em->flush();
        return $this->toArray($entity); // Devolvemos la entidad en forma de array
    }

    // Genera un array de arrays con todas las entidades
    public function entitiesToArray(array $entities)
    {
        if (is_null($entities))
            return null;
        $arr = [];
        foreach ($entities as $entity)
            $arr[] = $this->toArray($entity);
        return $arr;
    }

    // Borra el registro
    public function delete($entity)
    {
        $this->em->remove($entity);
        $this->em->flush();
    }
}
