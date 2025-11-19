<?php
declare(strict_types=1);

namespace Perfumeria\Modelo;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use DateTime;

#[ORM\Entity]
#[ORM\Table(name: "categoria")]
class Categoria
{
    #[ORM\Id]
    #[ORM\Column(name: "idCategoria", type: "bigint")]
    #[ORM\GeneratedValue]
    private int $idCategoria;

    #[ORM\Column(name: "nombre", type: "string", length: 255)]
    private string $nombre;

    // Relación 1:N → una categoría tiene muchos productos
    #[ORM\OneToMany(mappedBy: "categoria", targetEntity: Producto::class, cascade: ["persist", "remove"])]
    private Collection $productos;
    
    #[ORM\Column(name: "deshabilitado", type: "datetime", nullable: true)]
    private ?DateTime $deshabilitado = null;


    public function __construct()
    {
        $this->productos = new ArrayCollection();
    }

    // --- Getters ---
    public function getIdCategoria(): int { return $this->idCategoria; }
    public function getNombre(): string { return $this->nombre; }
    public function getProductos(): Collection { return $this->productos; }
    public function getDeshabilitado(): ?DateTime { return $this->deshabilitado; }

    // --- Setters ---
    public function setNombre(string $nombre): void { $this->nombre = $nombre; }
    public function setDeshabilitado(?DateTime $fecha): void { $this->deshabilitado = $fecha; }

    // --- Métodos para la relación ---
    public function addProducto(Producto $producto): void {
        if (!$this->productos->contains($producto)) {
            $this->productos->add($producto);
            $producto->setCategoria($this);
        }
    }

    public function removeProducto(Producto $producto): void {
        if ($this->productos->removeElement($producto)) {
            if ($producto->getCategoria() === $this ) {
                $producto->setCategoria(null);
            }
        }
    }
}