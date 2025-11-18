<?php
use Doctrine\ORM\EntityManager;

/**
 * Retorna un array con todos los datos enviados por POST, GET y FILES.
 * Sanitiza los valores de POST y GET para prevenir ataques XSS.
 *
 * @return array Array unificado con los datos.
 */
function datosEnviados(): array {
    // Función interna para sanitizar los datos.
    // Usar una función anónima evita repetir el bloque foreach.
    $sanitizar = function ($datos) {
        $sanitizado = [];
        foreach ($datos as $clave => $valor) {
            // is_array es para manejar inputs con corchetes, ej: name="colores[]"
            if (is_array($valor)) {
                $sanitizado[$clave] = array_map(fn($item) => htmlspecialchars(trim($item)), $valor);
            } else {
                $sanitizado[$clave] = htmlspecialchars(trim($valor));
            }
        }
        return $sanitizado;
    };

    // Sanitiza POST y GET usando la función anterior.
    $postSanitizado = $sanitizar($_POST);
    $getSanitizado = $sanitizar($_GET);

    // Opcional: Limpieza básica para nombres de archivo en $_FILES.
    $filesLimpios = [];
    if (!empty($_FILES)) {
        foreach ($_FILES as $clave => $archivo) {
            if (isset($archivo['name'])) {
                $archivo['name'] = preg_replace("/[^a-zA-Z0-9_\.-]/", "_", $archivo['name']);
            }
            $filesLimpios[$clave] = $archivo;
        }
    }

    // Unimos los tres arrays. Si hay claves duplicadas, POST sobreescribe a GET.
    return array_merge($getSanitizado, $postSanitizado, $filesLimpios);
}

/**
 * Retorna un array con los productos/clientes/administradores coincidentes con la busqueda.
 *
 * @return array Array de resultado.
 */
function buscarPorTermino(EntityManager $entidadManager, string $entidadBuscada, string $termino): array {
    // Obtengo el repositorio de la entidad que se busca
    $repositorio = $entidadManager->getRepository($entidadBuscada);
    $qb = $repositorio->createQueryBuilder('p'); // 'p' es el alias de 'Producto'

    // Construir consulta
    // busca en NOMBRE o DESCRIPCIÓN
    $consulta = $qb->where(
        $qb->expr()->orX( // 'orX' significa que CUALQUIERA de las dos es válida
            $qb->expr()->like('p.nombre', ':termino'),
            $qb->expr()->like('p.descripcion', ':termino')
        )
    )
    ->setParameter('termino', '%' . $termino . '%');

    // Ejecutar y devolver
    try {
        // Se retorna el resultado.
        return $consulta->getQuery()->getResult();

    } catch (\Exception $e) {
        error_log('Error en buscarPorTermino: ' . $e->getMessage());
        return []; // Devolver vacío en caso de error
    }
}