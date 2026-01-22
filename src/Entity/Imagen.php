<?php

namespace App\Entity;

use App\Repository\ImagenRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ImagenRepository::class)]
class Imagen
{
    const RUTA_IMAGENES_PORTFOLIO = 'images/index/portfolio/';
    const RUTA_IMAGENES_GALERIA = 'images/index/gallery/';
    const RUTA_IMAGENES_CLIENTES = 'images/clients/';
    const RUTA_IMAGENES_SUBIDAS = 'images/subidas/';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $nombre = null;

    #[ORM\Column(length: 200, nullable: true)]
    private ?string $descripcion = null;

    #[ORM\Column(nullable: true)]
    private ?int $numDownloads = null;

    #[ORM\Column]
    private ?int $numVisualizaciones = null;

    #[ORM\Column]
    private ?int $numLikes = null;

    #[ORM\ManyToOne(inversedBy: 'imagens', targetEntity: Categoria::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?Categoria $categoria = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $fecha = null;

    public function __construct($nombre = "", $descripcion = "", $numVisualizaciones = 0, $numLikes = 0, $numDownloads = 0)
    {
        $this->id = null;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->numVisualizaciones = $numVisualizaciones;
        $this->numLikes = $numLikes;
        $this->numDownloads = $numDownloads;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): static
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(?string $descripcion): static
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    public function getNumDownloads(): ?int
    {
        return $this->numDownloads;
    }

    public function setNumDownloads(?int $numDownloads): static
    {
        $this->numDownloads = $numDownloads;

        return $this;
    }

    public function getNumVisualizaciones(): ?int
    {
        return $this->numVisualizaciones;
    }

    public function setNumVisualizaciones(int $numVisualizaciones): static
    {
        $this->numVisualizaciones = $numVisualizaciones;

        return $this;
    }

    public function getNumLikes(): ?int
    {
        return $this->numLikes;
    }

    public function setNumLikes(int $numLikes): static
    {
        $this->numLikes = $numLikes;

        return $this;
    }
    public function getUrlPortfolio(): string
    {
        return self::RUTA_IMAGENES_PORTFOLIO . $this->getNombre();
    }

    public function getUrlGaleria(): string
    {
        return self::RUTA_IMAGENES_GALERIA . $this->getNombre();
    }

    public function getUrlClientes(): string
    {
        return self::RUTA_IMAGENES_CLIENTES . $this->getNombre();
    }

    public function getUrlSubidas(): string
    {
        return self::RUTA_IMAGENES_SUBIDAS . $this->getNombre();
    }


    public function __toString(): string
    {
        return $this->descripcion;
    }
    public function toArray(): array
    {
        return [
            'nombre' => $this->getNombre(),
            'descripcion' => $this->getDescripcion(),
            'numVisualizaciones' => $this->getNumVisualizaciones(),
            'numLikes' => $this->getNumLikes(),
            'numDownloads' => $this->getNumDownloads()
        ];
    }

    public function getCategoria(): ?Categoria
    {
        return $this->categoria;
    }

    public function setCategoria(?Categoria $categoria): static
    {
        $this->categoria = $categoria;

        return $this;
    }

    public function getFecha(): ?\DateTime
    {
        return $this->fecha;
    }

    public function setFecha(?\DateTime $fecha): static
    {
        $this->fecha = $fecha;

        return $this;
    }
}
