<?php
    declare (strict_types = 1);

    namespace App\Entity;

    use Doctrine\ORM\Mapping as ORM;

    #[ORM\Entity]
    #[ORM\Table (name: "adjunto")]
    class Adjunto {
        #[ORM\Id]
        #[ORM\Column (name: "idAdjunto", type: "bigint")]
        #[ORM\GeneratedValue]
        private int $idAdjunto;

        #[ORM\Column (name: "rutaUrl", type: "string", length: 500)]
        private string $rutaUrl;

        // Relación N:1 → muchos adjuntos pertenecen a un solo producto
        #[ORM\ManyToOne (targetEntity: Producto::class, inversedBy: "adjuntos")]
        #[ORM\JoinColumn (name: "idProducto", referencedColumnName: "idProducto", nullable: false)]
        private ?Producto $producto = null;

        // --- Getters ---
        public function getIdAdjunto (): int { return $this -> idAdjunto; }
        public function getRutaUrl (): string { return $this -> rutaUrl; }
        public function getProducto (): ?Producto { return $this -> producto; }

        // --- Setters ---
        public function setRutaUrl (string $rutaUrl): void { $this -> rutaUrl = $rutaUrl; }
        public function setProducto (?Producto $producto): void { $this -> producto = $producto; }
    }
?>