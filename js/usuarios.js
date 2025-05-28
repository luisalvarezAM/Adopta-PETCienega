document.addEventListener('DOMContentLoaded', function() {
    // Variables globales
    let usuarioAEliminar = null;

    // Inicialización
    actualizarContador();
    inicializarEventListeners();

    // Funciones principales
    function inicializarEventListeners() {

        document.getElementById('buscarUsuario').addEventListener('keyup', filtrarTabla);
        document.getElementById('filtroMunicipio').addEventListener('change', filtrarTabla); // Cambiado a "filtroMunicipio"
        document.getElementById('ordenarUsuario').addEventListener('change', ordenarTabla);
        inicializarBotonesEliminar();

        document.getElementById('btnLimpiarBusqueda').addEventListener('click', function() {
            document.getElementById('buscarUsuario').value = '';
            document.getElementById('filtroMunicipio').value = '';
            filtrarTabla();
            document.querySelectorAll('.btn-eliminar-admin').forEach(btn => {
                btn.addEventListener('click', prepararEliminacion);

            });
        });
        document.getElementById('confirmarEliminar').addEventListener('click', confirmarEliminacion);
    }

    function filtrarTabla() {
        const searchText = this.value.toLowerCase();
        const filas = document.querySelectorAll('#tablaUsuario tr');
        const filtroMunicipio = document.getElementById('filtroMunicipio').value.toLowerCase();

        filas.forEach(fila => {
            const textoFila = fila.textContent.toLowerCase();
            const municipioFila = fila.cells[6].textContent.toLowerCase();

            const coincideBusqueda = textoFila.includes(searchText) || searchText === '';
            const coincideMunicipio = municipioFila.includes(filtroMunicipio) || filtroMunicipio === '';

            fila.style.display = (coincideBusqueda && coincideMunicipio && searchText) ? '' : 'none';
        });

        actualizarContador();
    }

    function limpiarBusqueda() {
        document.getElementById('buscarUsuario').value = '';
        document.getElementById('filtroMunicipio').value = '';
        document.getElementById('buscarUsuario').dispatchEvent(new Event('keyup'));
        filtrarTabla();
    }

    function filtrarTabla() {
        const searchText = document.getElementById('buscarUsuario').value.toLowerCase();
        const filtroMunicipio = document.getElementById('filtroMunicipio').value.toLowerCase();
        const filas = document.querySelectorAll('#tablaUsuario tr');

        filas.forEach(fila => {
            const textoFila = fila.textContent.toLowerCase();
            const municipioFila = fila.cells[6].textContent.toLowerCase(); // Columna 6 = municipio

            const coincideBusqueda = textoFila.includes(searchText) || searchText === '';
            const coincideMunicipio = municipioFila.includes(filtroMunicipio) || filtroMunicipio === '';

            fila.style.display = (coincideBusqueda && coincideMunicipio) ? '' : 'none';
        });

        actualizarContador();
    }

    function ordenarTabla() {
        const orden = this.value;
        const tabla = document.getElementById('tablaUsuario');
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
                    aVal = new Date(a.cells[7].textContent.split('/').reverse().join('-'));
                    bVal = new Date(b.cells[7].textContent.split('/').reverse().join('-'));
                    return aVal - bVal;

                case 'fecha_desc':
                    aVal = new Date(a.cells[7].textContent.split('/').reverse().join('-'));
                    bVal = new Date(b.cells[7].textContent.split('/').reverse().join('-'));
                    return bVal - aVal;

                default:
                    return 0;
            }
        });

        // Reinsertar filas ordenadas
        filas.forEach(fila => tabla.appendChild(fila));
    }

    function prepararEliminacion(id) {
        usuarioAEliminar = id;
        $('#confirmarEliminarModal').modal('show');
    }

    function confirmarEliminacion() {
        if (usuarioAEliminar) {
            eliminarUsuario(usuarioAEliminar);
        }
        $('#confirmarEliminarModal').modal('hide');
    }

    function eliminarUsuario(id) {

        const form = document.createElement('form');
        form.method = 'POST';
        form.action = 'eliminar_usuario.php';

        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'id_usuario';
        input.value = id;
        form.appendChild(input);

        document.body.appendChild(form);
        form.submit();
    }

    function inicializarBotonesEliminar() {
        document.querySelectorAll('.btn-eliminar-admin').forEach(btn => {
            // Remover listeners antiguos para evitar duplicados
            btn.removeEventListener('click', manejarClickEliminar);
            // Agregar nuevo listener
            btn.addEventListener('click', manejarClickEliminar);
        });
    }

    function manejarClickEliminar() {
        const idUsuario = this.getAttribute('data-id') || this.closest('tr').getAttribute('data-id');
        prepararEliminacion(idUsuario);
    }

    function actualizarContador() {
        const visible = document.querySelectorAll('#tablaUsuario tr:not([style*="display: none"])').length;
        const contador = document.getElementById('contadorUsuario');
        contador.textContent = visible + ' usuario' + (visible !== 1 ? 'es' : '');
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