document.addEventListener('DOMContentLoaded', function() {
    let adminAEliminar = null;

    actualizarContador();
    inicializarEventListeners();

    // Funciones principales
    function inicializarEventListeners() {
        document.getElementById('buscarAdmin').addEventListener('keyup', filtrarTabla);
        document.getElementById('btnLimpiarBusqueda').addEventListener('click', limpiarBusqueda);
        document.getElementById('ordenarAdmin').addEventListener('change', ordenarTabla);

        // Solo un listener para los botones de eliminar
        document.querySelectorAll('.btn-eliminar-admin').forEach(boton => {
            boton.addEventListener('click', function(e) {
                e.preventDefault();
                adminAEliminar = this.getAttribute('data-id');
                $('#confirmarEliminarModal').modal('show');
            });
        });

        // Solo un listener para el botón de confirmación
        document.getElementById('confirmarEliminar').addEventListener('click', function() {
            if (adminAEliminar) {
                eliminarAdministrador(adminAEliminar);
            }
            $('#confirmarEliminarModal').modal('hide');
        });
    }


    function filtrarTabla() {
        const searchText = this.value.toLowerCase();
        const filas = document.querySelectorAll('#tablaAdministradores tr');

        filas.forEach(fila => {
            fila.style.display = fila.textContent.toLowerCase().includes(searchText) ? '' : 'none';
        });

        actualizarContador();
    }

    function limpiarBusqueda() {
        document.getElementById('buscarAdmin').value = '';
        document.getElementById('buscarAdmin').dispatchEvent(new Event('keyup'));
    }

    function filtrarPorFunciones() {
        const filtro = this.value;
        const filas = document.querySelectorAll('#tablaAdministradores tr');

        filas.forEach(fila => {
            if (filtro === '') {
                fila.style.display = '';
            } else {
                const funciones = fila.dataset.funciones;
                fila.style.display = funciones.includes(filtro) ? '' : 'none';
            }
        });

        actualizarContador();
    }

    function ordenarTabla() {
        const orden = this.value;
        const tabla = document.getElementById('tablaAdministradores');
        const filas = Array.from(tabla.querySelectorAll('tr'));

        filas.sort((a, b) => {
            let aVal, bVal;

            switch (orden) {
                case 'nombre_asc':
                    aVal = a.cells[3].textContent.toLowerCase();
                    bVal = b.cells[3].textContent.toLowerCase();
                    return aVal.localeCompare(bVal);

                case 'nombre_desc':
                    aVal = a.cells[3].textContent.toLowerCase();
                    bVal = b.cells[3].textContent.toLowerCase();
                    return bVal.localeCompare(aVal);

                case 'fecha_asc':
                    aVal = new Date(a.cells[6].textContent.split('/').reverse().join('-'));
                    bVal = new Date(b.cells[6].textContent.split('/').reverse().join('-'));
                    return aVal - bVal;

                case 'fecha_desc':
                    aVal = new Date(a.cells[6].textContent.split('/').reverse().join('-'));
                    bVal = new Date(b.cells[6].textContent.split('/').reverse().join('-'));
                    return bVal - aVal;

                default:
                    return 0;
            }
        });

        // Reinsertar filas ordenadas
        filas.forEach(fila => tabla.appendChild(fila));
    }

    function prepararEliminacion() {
        adminAEliminar = id;
        $('#confirmarEliminarModal').modal('show');
    }

    function confirmarEliminacion() {
        if (adminAEliminar) {
            eliminarAdministrador(adminAEliminar);
        }
        $('confirmarEliminarModal').modal('hide');
    }

    function eliminarAdministrador(id) {
        fetch('eliminar_administrador.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `id_usuario=${id}`
            })
            .then(response => {
                if (response.redirected) {
                    window.location.href = response.url;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                mostrarAlerta('error', 'Error al eliminar el administrador');
            });
    }

    function inicializarBotonesEliminar() {
        document.querySelectorAll('.btn-eliminar-admin').forEach(btn => {
            btn.removeEventListener('click', manejarClickEliminar);
            btn.addEventListener('click', manejarClickEliminar);
        });
    }

    function manejarClickEliminar() {
        const idUsuario = this.getAttribute('data-id') || this.closest('tr').getAttribute('data-id');
        prepararEliminacion(idUsuario);
    }

    function actualizarContador() {
        const visible = document.querySelectorAll('#tablaAdministradores tr:not([style*="display: none"])').length;
        const contador = document.getElementById('contadorAdmin');
        contador.textContent = visible + ' administrador' + (visible !== 1 ? 'es' : '');
    }

    function mostrarAlerta(tipo, mensaje) {
        // Eliminar alertas anteriores
        document.querySelectorAll('.alert.fixed-top').forEach(alerta => alerta.remove());

        // Crear alerta
        const alerta = document.createElement('div');
        alerta.className = `alert alert-${tipo} alert-dismissible fade show fixed-top mx-auto mt-3`;
        alerta.style.maxWidth = '600px';
        alerta.style.zIndex = '2000';
        alerta.role = 'alert';
        alerta.innerHTML = `
            ${mensaje}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;

        document.body.appendChild(alerta);

        // Auto-eliminar después de 5 segundos
        setTimeout(() => {
            alerta.classList.remove('show');
            setTimeout(() => alerta.remove(), 150);
        }, 5000);
    }
});