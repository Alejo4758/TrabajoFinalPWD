<?php
    declare (strict_types = 1);

    namespace App\Entity;

    use Doctrine\ORM\Mapping as ORM;
    use DateTime;

    #[ORM\Entity]
    #[ORM\Table (name: "refresh_token")]
    class RefreshToken {
        #[ORM\Id]
        #[ORM\Column (name:"idRefreshToken", type: "bigint")]
        #[ORM\GeneratedValue]
        private int $idRefreshToken;

        #[ORM\Column (name:"token", type: "string", length: 255, unique: true)]
        private string $token;

        #[ORM\Column (name:"fecha_expiracion", type: "datetime")]
        private DateTime $fechaExpiracion;

        #[ORM\ManyToOne (targetEntity: Usuario::class, inversedBy:"tokens")]
        #[ORM\JoinColumn (name: "idUsuario", referencedColumnName: "idUsuario", onDelete: "CASCADE")]
        private ?Usuario $usuario;

        // --- Getters ---
        public function getIdRefreshToken (): int { return $this -> idRefreshToken; }
        public function getToken (): string { return $this -> token; }
        public function getFechaExpiracion (): DateTime { return $this -> fechaExpiracion; }
        public function getUsuario (): ?Usuario { return $this -> usuario; }

        // --- Setters ---
        public function setToken (string $token): void { $this -> token = $token; }
        public function setFechaExpiracion (DateTime $fechaExpiracion): void { $this -> fechaExpiracion = $fechaExpiracion; }
        public function setUsuario (?Usuario $usuario): void { $this -> usuario = $usuario; }
    }
?>