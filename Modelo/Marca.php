<?php
declare(strict_types=1);

namespace Perfumeria\Modelo;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use DateTime;

#[ORM\Entity]
#[ORM\Table(name: "marca")]
class Marca
{
    #[ORM\Id]
    #[ORM\Column(name: "idMarca", type: "bigint")]
    #[ORM\GeneratedValue]
    private int $idMarca;

    #[ORM\Column(name: "nombre", type: "string", length: 255)]
    private string $nombre;

    // Una marca tiene muchos productos
    #[ORM\OneToMany(mappedBy: "marca", targetEntity: Producto::class, cascade: ["persist", "remove"])]
    private Collection $productos;

    #[ORM\Column(name: "deshabilitado", type: "datetime", nullable: true)]
    private ?DateTime $deshabilitado = null;

    public function __construct()
    {
        $this->productos = new ArrayCollection();
    }

    // --- Getters ---
    public function getIdMarca(): int { return $this->idMarca; }
    public function getNombre(): string { return $this->nombre; }
    public function getDeshabilitado(): ?DateTime { return $this->deshabilitado; }

    /** @return Collection<int, Producto> */
    public function getProductos(): Collection { return $this->productos; }

    // --- Setters ---
    public function setNombre(string $nombre): void { $this->nombre = $nombre; }
    public function setDeshabilitado(?DateTime $fecha): void { $this->deshabilitado = $fecha; }
    // --- Métodos de relación ---
    public function addProducto(Producto $producto): void{
        if (!$this->productos->contains($producto)) {
            $this->productos->add($producto);
            $producto->setMarca($this);
        }
    }

    public function removeProducto(Producto $producto): void {
        if ($this->productos->removeElement($producto)) {
            if ($producto->getMarca() === $this) {
                $producto->setMarca(null);
            }
        }
    }
}