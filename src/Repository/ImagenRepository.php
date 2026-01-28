<?php

namespace App\Repository;

use App\Entity\Imagen;
use App\Entity\User; // <--- IMPORTANTE: Importar la entidad User
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;

/**
 * @extends ServiceEntityRepository<Imagen>
 */
class ImagenRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Imagen::class);
    }

    public function remove(Imagen $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Función privada para filtrar por usuario si no es ADMIN
     */
    private function addUserFilter(QueryBuilder $qb, ?User $usuario)
    {
        // Si hay usuario y NO es admin, aplicamos el filtro
        if ($usuario && in_array('ROLE_ADMIN', $usuario->getRoles()) === false) {
            $qb->andWhere('imagen.usuario = :usuario')
               ->setParameter('usuario', $usuario);
        }
    }

    /**
     * Búsqueda avanzada de imágenes
     * He renombrado la función a plural 'findImagenes' y cambiado el alias 'i' por 'imagen'
     */
    public function findImagenes(?string $descripcion, ?string $fechaInicial, ?string $fechaFinal, ?User $usuario): array
    {
        // Usamos alias 'imagen' para ser consistentes con addUserFilter
        $qb = $this->createQueryBuilder('imagen'); 

        if (!is_null($descripcion) && $descripcion !== '') {
            $qb->andWhere(
                $qb->expr()->orX(
                    $qb->expr()->like('imagen.descripcion', ':val'),
                    $qb->expr()->like('imagen.nombre', ':val')
                )
            )
            ->setParameter('val', '%' . $descripcion . '%');
        }

        if (!is_null($fechaInicial) && $fechaInicial !== '') {
            $dtFechaInicial = \DateTime::createFromFormat('Y-m-d', $fechaInicial);
            $dtFechaInicial->setTime(0, 0, 0);
            $qb->andWhere($qb->expr()->gte('imagen.fecha', ':fechaInicial'))
                ->setParameter('fechaInicial', $dtFechaInicial);
        }

        if (!is_null($fechaFinal) && $fechaFinal !== '') {
            $dtFechaFinal = \DateTime::createFromFormat('Y-m-d', $fechaFinal);
            $dtFechaFinal->setTime(0, 0, 0);
            $qb->andWhere($qb->expr()->lte('imagen.fecha', ':fechaFinal'))
                ->setParameter('fechaFinal', $dtFechaFinal);
        }

        // Aplicamos el filtro de seguridad
        $this->addUserFilter($qb, $usuario);

        return $qb->getQuery()->getResult();
    }

    /**
     * Obtener imágenes con ordenación y categoría
     */
    public function findImagenesConCategoria(string $ordenacion, string $tipoOrdenacion, ?User $usuario)
    {
        $qb = $this->createQueryBuilder('imagen');
        $qb->addSelect('categoria')
            ->innerJoin('imagen.categoria', 'categoria')
            ->orderBy('imagen.' . $ordenacion, $tipoOrdenacion);
        
        // Aplicamos el filtro de seguridad
        $this->addUserFilter($qb, $usuario);

        return $qb->getQuery()->getResult();
    }
}