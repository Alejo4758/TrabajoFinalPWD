<?php
declare(strict_types=1);

namespace Perfumeria\Modelo;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;


#[ORM\Entity]
#[ORM\Table(name: "producto")]
class Producto
{
    #[ORM\Id]
    #[ORM\Column(name: "idProducto", type: "bigint")]
    #[ORM\GeneratedValue]
    private int $idProducto;

    #[ORM\Column(name: "nombre", type: "string", length: 255)]
    private string $nombre;

    #[ORM\Column(name: "precio", type: "decimal", precision: 10, scale: 2)]
    private float $precio;

    #[ORM\Column(name: "descripcion", type: "text", nullable: true)]
    private ?string $descripcion = null;

    #[ORM\Column(name: "codigoReferencia", type: "string", length: 100, unique: true)]
    private string $codigoReferencia;

    #[ORM\Column(name: "stock", type: "integer")]
    private int $stock;

    #[ORM\Column(name: "deshabilitado", type: "datetime", nullable: true)]
    private ?DateTime $deshabilitado = null;

    // --- Relaciones ---
    #[ORM\ManyToOne(targetEntity: Marca::class, inversedBy: "productos")]
    #[ORM\JoinColumn(name: "idMarca", referencedColumnName: "idMarca", nullable: false, onDelete: "RESTRICT")]
    private Marca $marca;

    #[ORM\ManyToOne(targetEntity: Categoria::class, inversedBy: "productos")]
    #[ORM\JoinColumn(name: "idCategoria", referencedColumnName: "idCategoria", nullable: false, onDelete: "RESTRICT")]
    private Categoria $categoria;

    #[ORM\OneToMany(mappedBy: "producto", targetEntity: Adjunto::class, cascade: ["persist", "remove"])]
    private Collection $adjuntos;

    #[ORM\OneToMany(mappedBy: "producto", targetEntity: ItemProducto::class, cascade: ["persist", "remove"])]
    private Collection $itemProductos;
    
    // --- Getters ---
    public function getIdProducto(): int { return $this->idProducto; }
    public function getNombre(): string { return $this->nombre; }
    public function getPrecio(): float { return $this->precio; }
    public function getDescripcion(): ?string { return $this->descripcion; }
    public function getCodigoReferencia(): string { return $this->codigoReferencia; }
    public function getStock(): int { return $this->stock; }
    public function getMarca(): Marca { return $this->marca; }
    public function getCategoria(): Categoria { return $this->categoria; }
    public function getDeshabilitado(): ?DateTime { return $this->deshabilitado;}

    // --- Setters ---
    public function setNombre(string $nombre): void { $this->nombre = $nombre; }
    public function setPrecio(float $precio): void { $this->precio = $precio; }
    public function setDescripcion(?string $descripcion): void { $this->descripcion = $descripcion; }
    public function setCodigoReferencia(string $codigoReferencia): void { $this->codigoReferencia = $codigoReferencia; }
    public function setStock(int $stock): void { $this->stock = $stock; }
    public function setMarca(?Marca $marca): void { $this->marca = $marca; }
    public function setCategoria(?Categoria $categoria): void { $this->categoria = $categoria; }
    public function setDeshabilitado(?DateTime $fecha): void { $this->deshabilitado = $fecha; }

    //--- Métodos para la relación ---
    public function __construct() { 
        $this->adjuntos = new ArrayCollection();
        $this->itemProductos = new ArrayCollection();
    }

    /** @return Collection<int, Adjunto> */
    public function getAdjuntos(): Collection { return $this->adjuntos;}
    public function getItemProductos(): Collection { return $this->itemProductos;}

    public function addAdjunto(Adjunto $adjunto): void {
        if (!$this->getAdjuntos()->contains($adjunto)) {
            $this->getAdjuntos()->add($adjunto);
            $adjunto->setProducto($this);
        }
    }

    public function addItemProducto(ItemProducto $itemProducto): void {
        if (!$this->getItemProductos()->contains($itemProducto)) {
            $this->getItemProductos()->add($itemProducto);
            $itemProducto->setProducto($this); // Sincroniza el lado inverso
        }
    }

    public function removeAdjunto(Adjunto $adjunto): void{
        if ($this->adjuntos->removeElement($adjunto)) {
            if ($adjunto->getProducto() === $this) {
            $adjunto->setProducto(null);
            }
        }
    }

    public function removeItemProducto(ItemProducto $itemProducto): void{
        if ($this->getItemProductos()->removeElement($itemProducto)) {
            // Si la entidad eliminada tenía este producto,
            // se setea a null para romper la relación.
            if ($itemProducto->getProducto() === $this) {
                $itemProducto->setProducto(null);
            }
        }
    }
}
