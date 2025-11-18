<?php
declare(strict_types=1);

namespace Perfumeria\Modelo;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "item_producto")]
class ItemProducto
{
    #[ORM\Id]
    #[ORM\Column(name: "idItemProducto", type: "bigint")]
    #[ORM\GeneratedValue]
    private int $idItem;

    #[ORM\Column(name: "cantidad", type: "integer")]
    private int $cantidad;

    #[ORM\Column(name: "precio", type: "decimal", precision: 10, scale: 2)]
    private float $precio;

    #[ORM\ManyToOne(targetEntity: Pedido::class, inversedBy: "itemsProductos")]
    #[ORM\JoinColumn(name: "idPedido", referencedColumnName: "idPedido", nullable: false, onDelete: "CASCADE")]
    private ?Pedido $pedido;

    #[ORM\ManyToOne(targetEntity: Producto::class, inversedBy: "itemsProductos")]
    #[ORM\JoinColumn(name: "idProducto", referencedColumnName: "idProducto", nullable: false, onDelete: "RESTRICT")]
    private ?Producto $producto;

    // --- Getters ---
    public function getIdItem(): int { return $this->idItem; }
    public function getCantidad(): int { return $this->cantidad; }
    public function getPrecio(): float { return $this->precio; }    
    public function getPedido(): ?Pedido { return $this->pedido; }
    public function getProducto(): ?Producto { return $this->producto; }

    // --- Setters ---
    public function setCantidad(int $cantidad): void { $this->cantidad = $cantidad; }
    public function setPrecio(float $precio): void { $this->precio = $precio; }
    public function setPedido(?Pedido $pedido): void { $this->pedido = $pedido; }
    public function setProducto(?Producto $producto): void { $this->producto = $producto; }
}