<?php
    declare (strict_types = 1);

    namespace Perfumeria\Modelo;

    use Doctrine\ORM\Mapping as ORM;
    use Doctrine\Common\Collections\ArrayCollection;
    use Doctrine\Common\Collections\Collection;

    #[ORM\Entity]
    #[ORM\Table (name: "categoria")]
    class Categoria {
        #[ORM\Id]
        #[ORM\Column (name: "idCategoria", type: "bigint")]
        #[ORM\GeneratedValue]
        private int $idCategoria;

        #[ORM\Column(name: "nombre", type: "string", length: 255)]
        private string $nombre;

        // Relación 1:N → una categoría tiene muchos productos
        #[ORM\OneToMany(mappedBy: "categoria", targetEntity: Producto::class)]
        private Collection $productos;


        // --- Constructor ---
        public function __construct () {
            $this -> productos = new ArrayCollection ();
        }

        // --- Getters ---
        public function getIdCategoria (): int { return $this -> idCategoria; }
        public function getNombre (): string { return $this -> nombre; }

        /** @return Collection<int, Producto> */
        public function getProductos (): Collection { return $this -> productos; }

        // --- Setters ---
        public function setNombre (string $nombre): void { $this -> nombre = $nombre; }

        // --- Métodos para la relación ---
        public function agregarProducto (Producto $producto): void {
            if (!$this -> getProductos () -> contains ($producto)) {
                $this -> getProductos () -> add ($producto);
                $producto -> setCategoria ($this);
            }
        }

        public function eliminarProducto (Producto $producto): void {
            if ($this -> getProductos () -> removeElement ($producto)) {
                if ($producto -> getCategoria () === $this ) {
                    $producto->setCategoria (null);
                } 
            }
        }
    }
?>