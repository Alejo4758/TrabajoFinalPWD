<?php
    declare (strict_types = 1);

    namespace Perfumeria\Modelo;

    use DateTime;
    use Doctrine\ORM\Mapping as ORM;
    use Doctrine\Common\Collections\ArrayCollection;
    use Doctrine\Common\Collections\Collection;

    use Perfumeria\Modelo\Marca;
    use Perfumeria\Modelo\Categoria;
    use Perfumeria\Modelo\Adjunto;
    use Perfumeria\Modelo\ItemProducto;

    #[ORM\Entity]
    #[ORM\Table (name: "Producto")]
    class Producto {
        #[ORM\Id]
        #[ORM\Column (name: "idProducto", type: "bigint")]
        #[ORM\GeneratedValue]
        private int $idProducto;

        #[ORM\Column (name: "nombre", type: "string", length: 255)]
        private string $nombre;

        #[ORM\Column (name: "precio", type: "decimal", precision: 10, scale: 2)]
        private string $precio;

        #[ORM\Column (name: "descripcion", type: "text")]
        private string $descripcion;

        #[ORM\Column (name: "codigoReferencia", type: "string", length: 100, unique: true)]
        private string $codigoReferencia;

        #[ORM\Column(name: "stock", type: "integer")]
        private int $stock;

        // --- Relaciones ---
        #[ORM\ManyToOne (targetEntity: Marca :: class, inversedBy: "Productos")]
        #[ORM\JoinColumn (name: "idMarca", referencedColumnName: "idMarca", nullable: false)]
        private Marca $marca;

        #[ORM\ManyToOne (targetEntity: Categoria :: class, inversedBy: "Productos")]
        #[ORM\JoinColumn (name: "idCategoria", referencedColumnName: "idCategoria", nullable: false)]
        private Categoria $categoria;

        #[ORM\OneToMany (mappedBy: "Producto", targetEntity: Adjunto :: class, cascade: ["persist", "remove"])]
        private Collection $adjuntos;

        #[ORM\OneToMany(mappedBy: "Producto", targetEntity: ItemProducto::class, cascade: ["persist", "remove"])]
        private Collection $itemsProducto;

        //--- Métodos para la relación ---
        public function __construct() { 
            $this -> adjuntos = new ArrayCollection ();
            $this -> itemsProducto = new ArrayCollection ();
        }
    
        // --- Getters ---
        public function getIdProducto (): int { return $this -> idProducto; }
        public function getNombre (): string { return $this -> nombre; }
        public function getPrecio (): string { return $this -> precio; }
        public function getDescripcion (): ?string { return $this -> descripcion; }
        public function getCodigoReferencia (): string { return $this -> codigoReferencia; }
        public function getStock (): int { return $this -> stock; }
        public function getMarca(): Marca { return $this->marca; }
        public function getCategoria(): Categoria { return $this->categoria; }

        // --- Setters ---
        public function setNombre (string $nombre): void { $this -> nombre = $nombre; }
        public function setPrecio (string $precio): void { $this -> precio = $precio; }
        public function setDescripcion (string $descripcion): void { $this -> descripcion = $descripcion; }
        public function setCodigoReferencia (string $codigoReferencia): void { $this -> codigoReferencia = $codigoReferencia; }
        public function setStock (int $stock): void { $this -> stock = $stock; }
        public function setMarca (?Marca $marca): void { $this -> marca = $marca; }
        public function setCategoria (?Categoria $categoria): void { $this -> categoria = $categoria; }

        // --- Métodos para la relación Adjunto ---

        /** @return Collection<int, Adjunto> */
        public function getAdjuntos (): Collection { return $this -> adjuntos;}

        public function agregarAdjunto (Adjunto $adjunto): void {
            $adjuntos = $this -> getAdjuntos ();
            if (!$adjuntos -> contains ($adjunto)) {
                $adjuntos -> add ($adjunto);
                $adjunto -> setProducto ($this);
            }
        }

        public function eliminarAdjunto (Adjunto $adjunto): void {
            $adjuntos = $this -> getAdjuntos ();
            if ($adjuntos -> removeElement ($adjunto)) {
                if ($adjunto -> getProducto () === $this) {
                    $adjunto -> setProducto (null);
                }
            }
        }

        // --- Métodos para la relación ItemProducto ---

        /** @return Collection<int, ItemProducto */
        public function getItemsProducto (): Collection { return $this -> itemsProducto;}

        public function agregarItemProducto (ItemProducto $itemProducto): void {
            $itemsProducto = $this -> getItemsProducto ();
            if (!$itemsProducto -> contains ($itemProducto)) {
                $itemsProducto -> add ($itemProducto);
                $itemProducto -> setProducto ($this);
            }
        }

        public function eliminarItemProducto (ItemProducto $itemProducto): void {
            if ($this -> getItemsProducto () -> removeElement ($itemProducto)) {
                if ($itemProducto -> getProducto () === $this) {
                    $itemProducto -> setProducto (null);
                }
            }
        }
    }
?>