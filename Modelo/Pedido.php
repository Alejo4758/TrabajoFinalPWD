<?php
declare(strict_types=1);

namespace Perfumeria\Modelo;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use DateTime;

#[ORM\Entity]
#[ORM\Table(name: "pedido")]
class Pedido
{
    #[ORM\Id]
    #[ORM\Column(name: "idPedido", type: "bigint")]
    #[ORM\GeneratedValue]
    private int $idPedido;

    #[ORM\Column(name: "fecha_pedido", type: "datetime")]
    private DateTime $fechaPedido;

    #[ORM\Column(name: "fecha_finalizado", type: "datetime", nullable: true)]
    private ?DateTime $fechaFinalizado = null;

    #[ORM\Column(name: "costo_envio", type: "decimal", precision: 10, scale: 2)]
    private float $costoEnvio;

    #[ORM\Column(name: "direccion_envio", type: "string", length: 255)]
    private string $direccion;

    #[ORM\Column(name: "metodo_envio", type: "string", length: 100)]
    private string $metodoEnvio;

    #[ORM\Column(name: "monto_total", type: "decimal", precision: 10, scale: 2)]
    private float $montoTotal;

    #[ORM\Column(name: "estado", type: "string", length: 50)]
    private string $estado;

    #[ORM\ManyToOne(targetEntity: Usuario::class, inversedBy: "pedidos")]
    #[ORM\JoinColumn(name: "idUsuario", referencedColumnName: "idUsuario", nullable: false)]
    private ?Usuario $usuario;

    #[ORM\OneToMany(mappedBy: "pedido", targetEntity: ItemProducto::class, cascade: ["persist", "remove"])]
    private Collection $itemsProductos;

    public function __construct() {
        $this->fechaPedido = new DateTime();
        $this->estado = "CARRITO"; 
        $this->itemsProductos = new ArrayCollection();
        $this->costoEnvio = 0.0;
        $this->montoTotal = 0.0;
        $this->direccion = "No especificada"; 
        $this->metodoEnvio = "No especificado";
    }

    // --- Getters ---
    public function getIdPedido(): int { return $this->idPedido; }
    public function getFechaPedido(): DateTime { return $this->fechaPedido; }
    public function getFechaFinalizado(): ?DateTime { return $this->fechaFinalizado; }
    public function getCostoEnvio(): float { return $this->costoEnvio; }
    public function getDireccion(): string { return $this->direccion; }
    public function getMetodoEnvio(): string { return $this->metodoEnvio; }
    public function getMontoTotal(): float { return $this->montoTotal; }
    public function getEstado(): string {return $this->estado; }
    public function getUsuario(): ?Usuario { return $this->usuario; }
    public function getItemsProducto(): Collection { return $this->itemsProductos; }

    // --- Setters ---
    public function setFechaFinalizado(DateTime $fechaFinalizado): void { $this->fechaFinalizado = $fechaFinalizado; }
    public function setCostoEnvio(float $costoEnvio): void { $this->costoEnvio = $costoEnvio; }
    public function setDireccion(string $direccion): void { $this->direccion = $direccion; }
    public function setMetodoEnvio(string $metodoEnvio): void { $this->metodoEnvio = $metodoEnvio; }
    public function setMontoTotal(float $montoTotal): void { $this->montoTotal = $montoTotal; }
    public function setEstado(string $estado): void { $this->estado = $estado; }
    public function setUsuario(?Usuario $usuario): void { $this->usuario = $usuario; }
    public function setItemsProductos(Collection $itemsProductos): void { $this->itemsProductos = $itemsProductos; }

    // --- Métodos de relación ItemsProducto ---
    public function agregarItemProducto(ItemProducto $itemProducto): void {
        if (!$this->getItemsProducto()->contains($itemProducto)) {
            $this->getItemsProducto()->add($itemProducto);
            $itemProducto->setPedido($this);
        }
    }

    public function eliminarItemProducto(ItemProducto $itemProducto): void {
        if ($this->getItemsProducto()->removeElement($itemProducto)) {
            if ($itemProducto->getPedido() === $this) {
                $itemProducto->setPedido(null);
            }
        }
    }
}