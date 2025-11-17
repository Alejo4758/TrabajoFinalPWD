<?php
    declare (strict_types = 1);

    namespace Perfumeria\Modelo;

    use Doctrine\ORM\Mapping as ORM;
    use Doctrine\Common\Collections\ArrayCollection;
    use Doctrine\Common\Collections\Collection;

    #[ORM\Entity]
    #[ORM\Table(name: "rol")]
    class Rol {
        #[ORM\Id]
        #[ORM\Column(name: "idRol", type: "bigint")]
        #[ORM\GeneratedValue]
        private int $idRol;

        #[ORM\Column(name: "nombre", type: "string", length: 255)]
        private string $nombre;

        #[ORM\OneToMany(mappedBy: "rol", targetEntity: Usuario::class)]
        private Collection $usuarios;

        // --- Constructor
        public function __construct () {
            $this -> usuarios = new ArrayCollection ();
        }

        // --- Getters ---
        public function getIdRol (): int { return $this -> idRol; }
        public function getNombre (): string { return $this -> nombre; }
        public function getUsuarios (): Collection { return $this -> usuarios; }

        // --- Setters ---
        public function setNombre (string $nombre): void { $this -> nombre = $nombre; }

        // --- Metodos de relación Usuario ---
        public function agregarUsuario (Usuario $usuario): void {
            $usuarios = $this -> getUsuarios ();
            if (!$usuarios -> contains ($usuario)) {
                $usuarios -> add ($usuario);
                $usuario -> setRol ($this);
            }
        }

        public function eliminarUsuario(Usuario $usuario): void {
            if ($this -> getUsuarios () -> removeElement ($usuario)) {
                if ($usuario -> getRol () === $this) {
                    $usuario -> setRol (null);
                }
            }
        }
    }
?>