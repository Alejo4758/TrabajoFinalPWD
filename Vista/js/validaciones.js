document.addEventListener('DOMContentLoaded', function () {
    const forms = document.querySelectorAll('.needs-validation');
    if (forms.length === 0) {
        console.error("No se encontró ningún formulario con .needs-validation");
    }
    Array.from(forms).forEach(function (form) {
        form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });
});
