<?php
namespace App\Entity;
use App\Entity\IEntity;

class Imagen implements IEntity
{
    const RUTA_IMAGENES_PORTFOLIO = '/dwes.local/public/images/index/portfolio/';
    const RUTA_IMAGENES_GALERIA = '/dwes.local/public/images/index/gallery/';
    const RUTA_IMAGENES_CLIENTES = '/dwes.local/public/images/clients/';
    const RUTA_IMAGENES_SUBIDAS = '/dwes.local/public/images/galeria/';

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $nombre;

    /**
     * @var string
     */
    private $descripcion;

    /**
     * @var int
     */
    private $categoria;

    /**
     * @var int
     */
    private $numVisualizaciones;

    /**
     * @var int
     */
    private $numLikes;

    /**
     * @var int
     */
    private $numDownloads;

    /**
     * @param string $nombre
     * @param string $descripcion
     * @param int $categoria
     * @param int $numVisualizaciones
     * @param int $numLikes
     * @param int $numDownloads
     */
    public function __construct(
        string $nombre = "",
        string $descripcion = "",
        int $categoria = 1,
        int $numVisualizaciones = 0,
        int $numLikes = 0,
        int $numDownloads = 0
    ) {

        $this->id = null;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->categoria = $categoria;
        $this->numVisualizaciones = $numVisualizaciones;
        $this->numLikes = $numLikes;
        $this->numDownloads = $numDownloads;
    }

    /**
     * @return int
     */
    public function getId(): ?int //? significa que puede devolver null
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getNombre(): ?string //? significa que puede devolver null
    {
        return $this->nombre;
    }

    /**
     * @param string $nombre
     * @return Imagen
     */
    public function setNombre(string $nombre): Imagen
    {
        $this->nombre = $nombre;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    /**
     * @param string $descripcion
     * @return Imagen
     */
    public function setDescripcion(string $descripcion): Imagen
    {
        $this->descripcion = $descripcion;
        return $this;
    }

    /**
     * @return int
     */
    public function getCategoria(): ?int
    {
        return $this->categoria;
    }

    /**
     * @param int $categoria
     * @return Imagen
     */
    public function setCategoria(int $categoria): Imagen
    {
        $this->categoria = $categoria;
        return $this;
    }

    /**
     * @return int
     */
    public function getNumVisualizaciones(): ?int
    {
        return $this->numVisualizaciones;
    }

    /**
     * @param string $numVisualizaciones
     * @return Imagen
     */
    public function setNumVisualizaciones(int $numVisualizaciones): Imagen
    {
        $this->numVisualizaciones = $numVisualizaciones;
        return $this;
    }

    /**
     * @return int
     */
    public function getNumLikes(): ?int
    {
        return $this->numLikes;
    }

    /**
     * @param string $numLikes
     * @return Imagen
     */
    public function setNumLikes(int $numLikes): Imagen
    {
        $this->numLikes = $numLikes;
        return $this;
    }

    /**
     * @return int
     */
    public function getNumDownloads(): ?int
    {
        return $this->numDownloads;
    }

    /**
     * @param string $numDownloads
     * @return Imagen
     */
    public function setNumDownloads(int $numDownloads): Imagen
    {
        $this->numDownloads = $numDownloads;
        return $this;
    }

    public function __toString(): string
    {
        //return $this->getDescripcion();
        return $this->descripcion;
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

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'nombre' => $this->getNombre(),
            'descripcion' => $this->getDescripcion(),
            'numVisualizaciones' => $this->getNumVisualizaciones(),
            'numLikes' => $this->getNumLikes(),
            'numDownloads' => $this->getNumDownloads(),
            'categoria' => $this->getCategoria()
        ];
    }
}
