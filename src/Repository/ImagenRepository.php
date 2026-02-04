<?php

namespace App\Repository;

use DateTime;
use App\Entity\Imagen;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\User; // <--- IMPORTANTE: Importar la entidad User
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

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

    public function findImagenes(?string $order, ?string $descripcion = null, ?string $fechaInicial = null, ?string $fechaFinal = null, ?User $usuario = null): array
    {
        $qb = $this->createQueryBuilder('imagen');

        // Filtro por Descripción o Nombre
        if (!is_null($descripcion) && $descripcion !== '') {
            $qb->andWhere(
                $qb->expr()->orX(
                    $qb->expr()->like('imagen.descripcion', ':val'),
                    $qb->expr()->like('imagen.nombre', ':val')
                )
            )
                ->setParameter('val', '%' . $descripcion . '%');
        }

        // Filtro por Fecha Inicial
        if (!is_null($fechaInicial) && $fechaInicial !== '') {
            $dtFechaInicial = DateTime::createFromFormat('Y-m-d', $fechaInicial);
            if ($dtFechaInicial) {
                $qb->andWhere($qb->expr()->gte('imagen.fecha', ':fechaInicial'))
                    ->setParameter('fechaInicial', $dtFechaInicial);
            }
        }

        // Filtro por Fecha Final
        if (!is_null($fechaFinal) && $fechaFinal !== '') {
            $dtFechaFinal = DateTime::createFromFormat('Y-m-d', $fechaFinal);
            if ($dtFechaFinal) {
                $qb->andWhere($qb->expr()->lte('imagen.fecha', ':fechaFinal'))
                    ->setParameter('fechaFinal', $dtFechaFinal);
            }
        }

        // Filtro por Usuario
        if (!is_null($usuario)) {
            $this->addUserFilter($qb, $usuario);
        }

        // Ordenación
        if (!is_null($order)) {
            $qb->addOrderBy('imagen.' . $order, 'ASC');
        }

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
