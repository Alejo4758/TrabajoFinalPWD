<?php
    // Tipado estricto
    declare (strict_types = 1);

    namespace Perfumeria\Control;

    // Dependencias
    use Perfumeria\Modelo\Usuario;
    use Perfumeria\Modelo\RefreshToken;
    use Doctrine\ORM\EntityManager;
    use Firebase\JWT\JWT;
    use Firebase\JWT\Key;
    use Firebase\JWT\ExpiredException;
    use DateTime;
    use Exception;

    // Cargo la configuracion
    require_once __DIR__ . '/../config/config.php';

    class Sesion {
        private ? object $datosUsuario = null; // Guardará el payload del token válido
        /**
         * Constructor
         * Carga la configuración y el EntityManager de Doctrine.
        */
        public function __construct (private string $claveSecreta, private EntityManager $entidadManager) {}

        // --- Getters ---
        public function getEntidadManager (): EntityManager { return $this -> entidadManager; }
        public function getClaveSecreta (): string { return $this -> claveSecreta; }
        public function getDatosUsuario (): ? object { return $this -> datosUsuario; }

        // --- Setters ---
        private function setDatosUsuario (object $datosUsuario): void { $this -> datosUsuario = $datosUsuario; }

        /**
         * Valida credenciales, crea tokens (Access y Refresh) y los guarda en cookies.
        */
        public function iniciar (string $nombreUsuario, string $contrasenia): bool {
            // Bandera
            $bandera = false;

            // Busca el usuario por su nombre usando el repositorio de Doctrine
            $usuario = $this -> getEntidadManager () -> getRepository (Usuario :: class) -> findOneBy (['username' => $nombreUsuario]);

            // Comparamos la contraseña ingresada con la del servidor
            if ($usuario && password_verify ($contrasenia, $usuario -> getContrasenia ())) {
                // 1. Access Token (JWT de corta duración: 15 minutos)
                $rol = $usuario -> getRol ();
                $roles = $rol ? [$rol -> getNombre ()] : [];
                $accessPayload = [
                    'usuario' => $usuario -> getUsername (),
                    'roles' => $roles,
                    'exp' => time () + 900

                ];

                // Creo el access token
                $accessToken = JWT :: encode ($accessPayload, $this -> getClaveSecreta (), 'HS256');

                // 2. Refresh Token (JWT de larga duración: 7 días)
                $refreshTokenString = bin2hex (random_bytes (32));
                $refreshTokenExpiracion = (new DateTime ()) -> modify ('+7 days');
            
                // Se crea y guarda RefreshToken en la BD
                $refreshToken = new RefreshToken ();
                $refreshToken -> setUsuario ($usuario);
                $refreshToken -> setToken ($refreshTokenString);
                $refreshToken -> setFechaExpiracion ($refreshTokenExpiracion);
            
                $this -> getEntidadManager () -> persist ($refreshToken);
                $this -> getEntidadManager () -> flush (); // Guarda los cambios

                // Enviar ambos tokens al navegador como cookies seguras
                setcookie (
                    'jwt_token',
                    $accessToken,
                    [
                        'expires' => time () + 900,
                        'path' => '/',
                        'httponly' => true,
                        'samesite' => 'Lax'
                    ]
                );

                setcookie (
                    'refresh_token',
                    $refreshTokenString,
                    [
                        'expires' => $refreshTokenExpiracion -> getTimestamp (),
                        'path' => '/',
                        'httponly' => true,
                        'samesite' => 'Lax'
                    ]
                );
                $bandera = true;
            }
            return $bandera;
        }

        /**
         * validar(). Valida si la sesión actual tiene un usuario y contraseña válidos.
         * Para JWT, esto significa que el Access Token es válido o puede ser refrescado.
        */
        public function validar (): bool {
            // Bandera
            $bandera = false;

            // Si NO tenemos la jwt_token devolvemos false
            if (isset ($_COOKIE['jwt_token'])) {
                try {
                    // Obtenemos el token
                    $token = $_COOKIE['jwt_token'];

                    // Verificamos el token. Si es correcto guardamos la información
                    $this -> setDatosUsuario (JWT :: decode ($token, new Key ($this -> getClaveSecreta (), 'HS256')));

                    $bandera = true;
                } catch (ExpiredException $e) {
                    // El Access Token expiró, intentamos refrescarlo silenciosamente
                    $bandera = $this -> refrescarAccessToken ();
                } catch (Exception $e) {
                    // El token es inválido por otra razón (firma, etc.)
                    $bandera = false;
                }
            }else {
                $bandera = false;
            }

            return $bandera;
        }

        /**
         * activa(). Devuelve true o false si la sesión está activa o no.
         * En nuestro caso, es un alias de validar().
         */
        public function activa (): bool {
            return $this -> getDatosUsuario () !== null || $this -> validar ();
        }

        /**
         * getUsuario(). Devuelve el usuario logeado.
         */
        public function getUsuario (): ? string { return $this -> datosUsuario?-> usuario; }

        /**
         * getRol(). Devuelve el rol del usuario logeado.
         * Como un usuario puede tener varios roles, devolvemos un array.
         */
        public function getRol (): ?array { return $this -> datosUsuario?-> roles; }

        /**
         * cerrar(). Cierra la sesión actual.
         * Borra las cookies y el refresh token de la BD.
         */
        public function cerrar (): void {
            // Si hay token...
            if (isset ($_COOKIE['refresh_token'])) {
                // Obtengo el token
                $token = $this -> getEntidadManager () -> getRepository (RefreshToken :: class) -> findOneBy (['token' => $_COOKIE['refresh_token']]);
                if ($token){
                    // Lo borro de la BD
                    $this -> getEntidadManager () -> remove ($token);
                    $this -> getEntidadManager () -> flush ();
                }
            }
            // Borro las cookie
            setcookie ('jwt_token', '', time() - 3600, '/');
            setcookie ('refresh_token', '', time() - 3600, '/');
        }
        
        /**
         * Lógica interna para usar el Refresh Token y generar un nuevo Access Token.
         */
        private function refrescarAccessToken (): bool {
            // Bandera
            $bandera = false;

            // Si hay token...
            if (isset ($_COOKIE['refresh_token'])){
                // Obtengo el token
                $token = $this -> entidadManager -> getRepository (RefreshToken :: class) -> findOneBy (['token' => $_COOKIE['refresh_token']]);

                // Verifica que el token exista en la BD y no haya expirado
                if ($token && $token -> getFechaExpiracion () > new DateTime ()) {
                    // Obtenemos el usuario
                    $usuario = $token -> getUsuario ();

                    // Generamos un nuevo Access Token
                    $rol = $usuario -> getRol ();
                    $roles = $rol ? [$rol -> getNombre ()] : [];
                    
                    $accessPayload = [
                        'usuario' => $usuario -> getUsername (),
                        'roles' => $roles,
                        'exp' => time () + 900

                    ];
                    // Creo el access token
                    $newAccessToken = JWT :: encode ($accessPayload, $this -> getClaveSecreta (), 'HS256');
                    
                    // Guardo el token en la cookie
                    setcookie (
                        'jwt_token',
                        $newAccessToken,
                        [
                            'expires' => time () + 900,
                            'path' => '/',
                            'httponly' => true,
                            'samesite' => 'Lax'
                        ]
                    );
                    
                    // Guardamos los datos del usuario para usarlos en la página actual
                    $this -> setDatosUsuario ((object)$accessPayload);
                    
                    $bandera = true;
                }else {
                    $this -> cerrar ();
                }
            }
            
            // Si el refresh token no es válido, cerramos la sesión por seguridad
            return $bandera;
        }
    }
?>