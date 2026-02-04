<?php

namespace App\BLL;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

abstract class BaseBLL
{
    protected EntityManagerInterface $em;
    protected ValidatorInterface $validator;
    protected RequestStack $requestStack;
    protected Security $security;
    protected UserPasswordHasherInterface $encoder;

    public function __construct(
        EntityManagerInterface $em,
        ValidatorInterface $validator,
        UserPasswordHasherInterface $encoder,
        RequestStack $requestStack,
        Security $security
    ) {
        $this->em = $em;
        $this->validator = $validator;
        $this->encoder = $encoder;
        $this->requestStack = $requestStack;
        $this->security = $security;
    }

    // --- AÑADIR ESTA LÍNEA PARA SOLUCIONAR EL ERROR ---
    abstract public function toArray($entity);
    // --------------------------------------------------

    private function validate($entity)
    {
        $errors = $this->validator->validate($entity);
        if (count($errors) > 0) {
            $strError = '';
            foreach ($errors as $error) {
                if (!empty($strError)) $strError .= '\n';
                $strError .= $error->getMessage();
            }
            throw new BadRequestHttpException($strError);
        }
    }

    protected function guardaValidando($entity): array
    {
        $this->validate($entity);
        $this->em->persist($entity);
        $this->em->flush();
        
        // Ahora esto ya no da error porque hemos declarado arriba que existe toArray
        return $this->toArray($entity);
    }

    public function entitiesToArray(array $entities)
    {
        if (is_null($entities)) return null;
        $arr = [];
        foreach ($entities as $entity)
            $arr[] = $this->toArray($entity);
        return $arr;
    }

    public function delete($entity)
    {
        $this->em->remove($entity);
        $this->em->flush();
    }
}