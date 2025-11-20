document.addEventListener('DOMContentLoaded', function() {
                
                // Obtenemos el contenido crudo (con HTML classes) para detectar el tipo
                let contenidoOriginal = "<?= addslashes($_SESSION['mensaje']) ?>";
                
                // Obtenemos solo el texto limpio (sin <div> ni tags) para mostrar
                let textoMensaje = "<?= addslashes(strip_tags($_SESSION['mensaje'])) ?>";

                // Definimos el icono por defecto
                let icono = 'info';

                // Detectamos el tipo de alerta basándonos en las clases de Bootstrap que usaste en el controlador
                if (contenidoOriginal.includes('alert-success')) {
                    icono = 'success';
                } else if (contenidoOriginal.includes('alert-danger')) {
                    icono = 'error';
                } else if (contenidoOriginal.includes('alert-warning')) {
                    icono = 'warning';
                }

                // Lanzamos la alerta tipo "Toast" (Notificación pequeña en la esquina)
                Swal.fire({
                    icon: icono,
                    title: textoMensaje,
                    toast: true,
                    position: 'top-end', // Arriba a la derecha
                    showConfirmButton: false,
                    timer: 3500, // Dura 3.5 segundos
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                });
            });