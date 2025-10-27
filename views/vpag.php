<?php
?>
<style>
/* ===== ESTILOS GENERALES ===== */
body {
    background-color: #212529 !important;
    color: #f8f9fa;
    font-family: 'Segoe UI', sans-serif;
}

/* Overlay de carga */
.loading-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.65);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 3000;
}

.spinner-border {
    width: 3rem;
    height: 3rem;
}

/* ===== NOTIFICACIONES ===== */
.alerta-flotante {
    position: fixed;
    top: 25px;
    left: 50%;
    transform: translateX(-50%);
    z-index: 5000;
    padding: 15px 25px;
    border-radius: 8px;
    font-weight: 600;
    text-align: center;
    color: #212529;
    background-color: #ffc107;
    box-shadow: 0 0 15px rgba(255,193,7,0.4);
    opacity: 0;
    transition: opacity 0.4s ease-in-out;
}
.alerta-flotante.mostrar {
    opacity: 1;
}

/* ===== CONTENEDOR PRINCIPAL ===== */
.container-fluid {
    padding-top: 80px;
    padding-bottom: 20px;
}

/* ===== CARD PRINCIPAL ===== */
.card.bg-secondary {
    background-color: rgba(108, 117, 125, 0.85) !important;
    color: #fff;
    border: none;
    box-shadow: 0 0 25px rgba(255, 193, 7, 0.15);
    border-radius: 10px;
}

/* ===== TABLA ===== */
.table-dark {
    background-color: rgba(33, 37, 41, 0.95);
    color: #f8f9fa;
    border-radius: 6px;
    overflow: hidden;
}
.table-dark th,
.table-dark td {
    vertical-align: middle;
}
.table-warning {
    background-color: #ffc107 !important;
    color: #212529 !important;
}

/* ===== BOTONES ===== */
.btn-warning {
    color: #212529;
    font-weight: 600;
    transition: all 0.2s ease-in-out;
}
.btn-warning:hover {
    background-color: #ffca2c;
    transform: scale(1.05);
}

/* ===== MODAL ===== */
#modalPagina {
    z-index: 4000;
}
#modalPagina .modal-dialog {
    z-index: 4001;
}
#modalPagina .modal-content {
    background-color: rgba(108, 117, 125, 0.9);
    color: #fff;
    border: 1px solid #ffc107;
    box-shadow: 0 0 25px rgba(255, 193, 7, 0.3);
}
#modalPagina .form-control,
#modalPagina .form-select {
    background-color: #212529;
    color: #f8f9fa;
    border: 1px solid #444;
}
#modalPagina .form-control:focus,
#modalPagina .form-select:focus {
    border-color: #ffc107;
    box-shadow: 0 0 5px #ffc107;
}
#modalPagina .btn-warning {
    font-weight: 600;
}
#modalPagina .btn-close-white {
    filter: invert(1);
}
</style>

<!-- Overlay -->
<div class="loading-overlay" id="loadingOverlay">
    <div class="spinner-border text-warning" role="status">
        <span class="visually-hidden">Cargando...</span>
    </div>
</div>

<!-- Mensaje flotante -->
<div id="mensajeFlotante" class="alerta-flotante"></div>

<!-- ===== ENCABEZADO PRINCIPAL ===== -->
<div class="container-fluid px-4 py-5">
    <div class="text-center mb-4">
        <h1 class="display-5 fw-bold text-warning">Gestión de Páginas SIMBA</h1>
        <p class="lead text-light">Administra las páginas del sistema, agrega nuevas o edita las existentes.</p>
    </div>

    <!-- ===== CARD PRINCIPAL ===== -->
    <div class="card bg-secondary text-white shadow-lg p-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="mb-0"><i class="fa-solid fa-globe me-2 text-warning"></i>Gestión de Páginas</h4>
                <button class="btn btn-warning" onclick="abrirModalNuevo()">
                    <i class="fa-solid fa-plus me-1"></i> Nueva Página
                </button>
            </div>
            <div class="table-responsive">
                <table class="table table-dark table-hover align-middle text-center mb-0">
                    <thead class="table-warning text-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Ruta</th>
                            <th>Mostrar</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tablaPaginas"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- ===== MODAL ===== -->
<div class="modal fade" id="modalPagina" tabindex="-1" aria-labelledby="modalPaginaLabel" aria-hidden="true" data-bs-backdrop="false" data-bs-keyboard="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="formPagina">
                <div class="modal-header border-warning">
                    <h5 class="modal-title" id="modalPaginaLabel">
                        <i class="fa-solid fa-file-pen text-warning me-2"></i>
                        <span id="modalTitulo">Nueva Página</span>
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="idpag" name="idpag">
                    <div class="mb-3">
                        <label class="form-label">Nombre de la página</label>
                        <input type="text" id="nompag" name="nompag" class="form-control" placeholder="Ejemplo: Página de inicio" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ruta del archivo</label>
                        <input type="text" id="rutpag" name="rutpag" class="form-control" placeholder="Ejemplo: vhome.php" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">¿Mostrar en menú?</label>
                        <select id="mospag" name="mospag" class="form-select">
                            <option value="1">Sí</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-warning">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fa-solid fa-xmark me-1"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fa-solid fa-floppy-disk me-1"></i> Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
const formPagina = document.getElementById('formPagina');
const modalEl = document.getElementById('modalPagina');
const tablaPaginas = document.getElementById('tablaPaginas');
const overlay = document.getElementById('loadingOverlay');
const mensajeFlotante = document.getElementById('mensajeFlotante');
let modal;

// Mostrar mensaje flotante elegante
function mostrarMensaje(texto, tipo = 'info') {
    mensajeFlotante.textContent = texto;
    mensajeFlotante.style.backgroundColor = tipo === 'error' ? '#dc3545' : '#ffc107';
    mensajeFlotante.style.color = tipo === 'error' ? '#fff' : '#212529';
    mensajeFlotante.classList.add('mostrar');
    setTimeout(() => mensajeFlotante.classList.remove('mostrar'), 2000);
}

function ensureModal() {
    if (window.bootstrap && modalEl) {
        if (modalEl.parentElement !== document.body) {
            document.body.appendChild(modalEl);
        }
        modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl, {
            backdrop: true,
            keyboard: true,
            focus: true
        });
    }
    return modal;
}

function sanitizeModalInteractivity() {
    try {
        modalEl.removeAttribute('aria-hidden');
        modalEl.removeAttribute('inert');
        let p = modalEl;
        while ((p = p.parentElement)) {
            if (p.getAttribute && p.getAttribute('aria-hidden') === 'true') p.removeAttribute('aria-hidden');
            if (p.hasAttribute && p.hasAttribute('inert')) p.removeAttribute('inert');
        }
        formPagina.querySelectorAll('input, select, textarea, button').forEach(el => {
            el.disabled = false;
            el.removeAttribute('aria-disabled');
            el.removeAttribute('readonly');
            el.classList.remove('disabled');
        });
    } catch (e) { console.warn('sanitizeModalInteractivity error', e); }
}

document.addEventListener('DOMContentLoaded', () => {
    ensureModal();
    modalEl.addEventListener('show.bs.modal', sanitizeModalInteractivity);
    modalEl.addEventListener('shown.bs.modal', () => {
        sanitizeModalInteractivity();
        document.getElementById('nompag')?.focus();
    });
    overlay.style.display = 'none';
    cargarPaginas();
});

async function cargarPaginas() {
    try {
        const resp = await fetch('controllers/cpag.php', {
            method: 'POST',
            body: new URLSearchParams({ accion: 'listar' })
        });
        const html = await resp.text();
        if (!resp.ok) {
            tablaPaginas.innerHTML = "<tr><td colspan='5'>Error HTTP al cargar páginas (" + resp.status + ")</td></tr>";
            return;
        }
        tablaPaginas.innerHTML = html;
    } catch {
        tablaPaginas.innerHTML = "<tr><td colspan='5'>Error al cargar páginas.</td></tr>";
    }
}

function abrirModalNuevo() {
    formPagina.reset();
    document.getElementById('idpag').value = '';
    document.getElementById('modalTitulo').textContent = 'Nueva Página';
    ensureModal();
    overlay.style.display = 'none';
    sanitizeModalInteractivity();
    modal.show();
    setTimeout(() => document.getElementById('nompag')?.focus(), 150);
}

formPagina.addEventListener('submit', async (e) => {
    e.preventDefault();
    if (!formPagina.checkValidity()) return formPagina.reportValidity();
    const idpag = document.getElementById('idpag').value.trim();
    const nompag = document.getElementById('nompag').value.trim();
    const rutpag = document.getElementById('rutpag').value.trim();
    const mospag = document.getElementById('mospag').value.trim();
    const accion = idpag ? 'actualizar' : 'insertar';
    const body = new URLSearchParams({ accion, idpag, nompag, rutpag, mospag });
    try {
        const resp = await fetch('controllers/cpag.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
            body
        });
        const text = await resp.text();
        const data = JSON.parse(text);
        mostrarMensaje(data.mensaje || 'Operación realizada');
        modal.hide();
        formPagina.reset();
        cargarPaginas();
    } catch {
        mostrarMensaje('Error al guardar la página', 'error');
    }
});

function editarPagina(id, nombre, ruta, mostrar) {
    document.getElementById('idpag').value = id;
    document.getElementById('nompag').value = nombre;
    document.getElementById('rutpag').value = ruta;
    document.getElementById('mospag').value = mostrar;
    document.getElementById('modalTitulo').textContent = 'Editar Página';
    ensureModal();
    modal.show();
    setTimeout(() => document.getElementById('nompag')?.focus(), 150);
}

async function eliminarPagina(id) {
    if (!confirm('¿Seguro que deseas eliminar esta página?')) return;
    overlay.style.display = 'flex';
    const formData = new FormData();
    formData.append('accion', 'eliminar');
    formData.append('idpag', id);
    try {
        const resp = await fetch('controllers/cpag.php', { method: 'POST', body: formData });
        const data = await resp.json();
        mostrarMensaje(data.mensaje || 'Página eliminada');
        cargarPaginas();
    } catch {
        mostrarMensaje('Error al eliminar la página', 'error');
    } finally {
        overlay.style.display = 'none';
    }
}
</script>
