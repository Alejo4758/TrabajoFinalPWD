<?php
    declare (strict_types = 1);

    namespace App\Entity;

    use Doctrine\ORM\Mapping as ORM;
    use Doctrine\Common\Collections\ArrayCollection;
    use Doctrine\Common\Collections\Collection;
    use DateTime;

    #[ORM\Entity]
    #[ORM\Table (name: "usuario")]
    class Usuario {
        #[ORM\Id]
        #[ORM\Column (name: "idUsuario", type: "bigint")]
        #[ORM\GeneratedValue]
        private int $idUsuario;

        #[ORM\ManyToOne (targetEntity: Rol::class, inversedBy: "usuarios")]
        #[ORM\JoinColumn (name: "idRol", referencedColumnName: "idRol", nullable: false)]
        private ?Rol $rol;

        #[ORM\Column (name: "direccion_usuario", type: "string", length: 255)]
        private string $direccion;

        #[ORM\Column (name: "apellido", type: "string", length: 50)]
        private string $apellido;

        #[ORM\Column (name: "nombre", type: "string", length: 50)]
        private string $nombre;

        #[ORM\Column (name: "contrasenia_hash", type: "string", length: 255)]
        private string $contrasenia;

        #[ORM\Column (name: "email", type: "string", length: 50, unique: true)]
        private string $email;

        #[ORM\Column (name: "username", type: "string", length: 50, unique: true)]
        private string $username;

        #[ORM\Column (name: "fecha_creacion", type: "datetime")]
        private DateTime $fechaCreacion;

        #[ORM\Column (name: "deshabilitado", type: "datetime", nullable: true)]
        private ?DateTime $deshabilitado = null;

        #[ORM\OneToMany (mappedBy: "usuario", targetEntity: Pedido::class, cascade: ["persist", "remove"])]
        private Collection $pedidos;

        #[ORM\OneToMany (mappedBy: "usuario", targetEntity: RefreshToken::class, cascade: ["remove"], orphanRemoval: true)]
        private Collection $tokens;

        // --- Constructor ---
        public function __construct () {
            $this -> fechaCreacion = new DateTime ();
            $this -> pedidos = new ArrayCollection ();
            $this -> tokens = new ArrayCollection ();
        }

        // --- Getters ---
        public function getIdUsuario (): int { return $this -> idUsuario; }
        public function getRol (): ?Rol { return $this -> rol; }
        public function getDireccion (): string { return $this -> direccion; }
        public function getApellido (): string { return $this -> apellido; }
        public function getNombre (): string { return $this -> nombre; }
        public function getContrasenia (): string { return $this -> contrasenia; }
        public function getEmail (): string { return $this -> email; }
        public function getUsername (): string { return $this -> username; }
        public function getFechaCreacion (): DateTime { return $this -> fechaCreacion; }
        public function getDeshabilitado (): ?DateTime { return $this -> deshabilitado; }
        public function getPedidos (): Collection { return $this -> pedidos; }
        public function getTokens (): Collection { return $this -> tokens; }

        // --- Setters ---
        public function setRol (?Rol $rol): void { $this -> rol = $rol; }
        public function setDireccion (string $direccion): void{ $this -> direccion = $direccion; }
        public function setApellido (string $apellido): void { $this -> apellido = $apellido; }
        public function setNombre (string $nombre): void { $this -> nombre = $nombre; }
        public function setContrasenia (string $contrasenia): void { $this -> contrasenia = $contrasenia; }
        public function setEmail (string $email): void { $this -> email = $email; }
        public function setUsername (string $username): void { $this -> username = $username; }
        public function setDeshabilitado (DateTime $fechaDeshabilitado): void { $this -> deshabilitado = $fechaDeshabilitado; }
    
        // --- Métodos de relación Pedido ---
        public function agregarPedido (Pedido $pedido): void {
            $pedidos = $this -> getPedidos ();
            if (!$pedidos -> contains ($pedido)) {
                $pedidos -> add ($pedido);
                $pedido -> setUsuario ($this);
            }
        }

        public function eliminarPedido (Pedido $pedido): void {
            if ($this -> getPedidos () -> removeElement ($pedido)) {
                if ($pedido -> getUsuario () === $this) {
                    $pedido -> setUsuario (null);
                }
            }
        }

        // --- Métodos de relación Refresh_tokens ---
        public function agregarToken (RefreshToken $token): void {
            $tokens = $this -> getTokens ();
            if (!$tokens -> contains ($token)) {
                $tokens -> add ($token);
                $token -> setUsuario ($this);
            }
        }

        public function eliminarToken (RefreshToken $token): void {
            if ($this -> getTokens () -> removeElement ($token)) {
                if ($token -> getUsuario () === $this) {
                    $token->setUsuario(null);
                }
            }
        }
    }
?>