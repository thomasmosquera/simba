<?php
?>
<style>
    .loading-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.65);
        display: none;
        pointer-events: none; /* no bloquear cuando está oculto */
        align-items: center;
        justify-content: center;
        z-index: 3000;
    }
    .spinner-border {
        width: 3rem;
        height: 3rem;
    }
    /* Asegura interactividad dentro del modal */
    #modalPagina { z-index: 4000; }
    #modalPagina .modal-dialog { z-index: 4001; }
    #modalPagina .modal-content { pointer-events: auto; z-index: 4002; }
    #modalPagina * { pointer-events: auto !important; }
</style>

<div class="loading-overlay" id="loadingOverlay">
    <div class="spinner-border text-warning" role="status">
        <span class="visually-hidden">Cargando...</span>
    </div>
</div>

<div class="container mt-5">
    <div class="card bg-dark text-white border-warning shadow">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0"><i class="fa-solid fa-globe me-2 text-warning"></i>Gestión de Páginas</h4>
            <button class="btn btn-warning" onclick="abrirModalNuevo()">
                <i class="fa-solid fa-plus me-1"></i> Nueva Página
            </button>
        </div>
        <div class="card-body">
            <table class="table table-dark table-hover align-middle text-center">
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

<!-- MODAL -->
<div class="modal fade" id="modalPagina" tabindex="-1" aria-labelledby="modalPaginaLabel" aria-hidden="true" data-bs-backdrop="false" data-bs-keyboard="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark text-white border-warning">
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
                        <input type="text" id="nompag" name="nompag" class="form-control" placeholder="Ejemplo: Página de inicio" required autofocus tabindex="0">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ruta del archivo</label>
                        <input type="text" id="rutpag" name="rutpag" class="form-control" placeholder="Ejemplo: vhome.php" required tabindex="0">
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
let modal; // será inicializado cuando Bootstrap esté disponible
const overlay = document.getElementById('loadingOverlay');
const tablaPaginas = document.getElementById('tablaPaginas');

// Cargar datos al iniciar (sin manipular backdrops manualmente)
function ensureModal() {
    if (window.bootstrap && modalEl) {
        // Mueve el modal al <body> para evitar problemas de stacking/pointer en contenedores anidados
        if (modalEl.parentElement !== document.body) {
            document.body.appendChild(modalEl);
        }
        // Crea con opciones explícitas para manejo de foco correcto
        modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl, {
            backdrop: true,
            keyboard: true,
            focus: true
        });
    }
    return modal;
}

// Sanea atributos que puedan bloquear la interacción
function sanitizeModalInteractivity() {
    try {
        modalEl.removeAttribute('aria-hidden');
        modalEl.removeAttribute('inert');
        // Si algún ancestro del modal tiene aria-hidden, quítalo
        let p = modalEl;
        while ((p = p.parentElement)) {
            if (p.getAttribute && p.getAttribute('aria-hidden') === 'true') {
                p.removeAttribute('aria-hidden');
            }
            if (p.hasAttribute && p.hasAttribute('inert')) {
                p.removeAttribute('inert');
            }
        }
        // Habilita todos los controles del formulario por si algún estado previo los dejó disabled
        formPagina.querySelectorAll('input, select, textarea, button').forEach(el => {
            el.disabled = false;
            el.removeAttribute('aria-disabled');
            el.removeAttribute('readonly');
            el.classList.remove('disabled');
        });
    } catch (e) { console.warn('sanitizeModalInteractivity error', e); }
}

document.addEventListener('DOMContentLoaded', () => {
    // Inicializa el modal cuando Bootstrap ya está cargado
    ensureModal();
    // Listeners para mantenerlo interactivo
    modalEl.addEventListener('show.bs.modal', () => {
        sanitizeModalInteractivity();
    });
    modalEl.addEventListener('shown.bs.modal', () => {
        sanitizeModalInteractivity();
        const first = document.getElementById('nompag');
        if (first) first.focus();
        console.log('[modal] shown; aria-hidden=', modalEl.getAttribute('aria-hidden'));
    });
    // Asegura que el overlay nunca quede visible al cargar
    overlay.style.display = 'none';
    cargarPaginas();
});

// Forzar enfoque al hacer click dentro del modal aunque algún estilo bloquee
modalEl.addEventListener('click', (ev) => {
    const target = ev.target.closest('input, select, textarea, button');
    if (target) {
        target.disabled = false;
        target.removeAttribute('aria-disabled');
        target.removeAttribute('readonly');
        target.focus({ preventScroll: true });
        console.log('[modal click] focus en', target.id || target.name || target.tagName);
    }
}, true);

// Salvavidas: cualquier foco dentro del modal revalida interactividad
document.addEventListener('focusin', (ev) => {
    const t = ev.target;
    if (!modalEl.contains(t)) return;
    try {
        // Limpia atributos en ancestros que puedan bloquear
        let p = t;
        while (p) {
            if (p.getAttribute && p.getAttribute('aria-hidden') === 'true') p.removeAttribute('aria-hidden');
            if (p.hasAttribute && p.hasAttribute('inert')) p.removeAttribute('inert');
            p = p.parentElement;
        }
        // Habilita el control que recibe foco
        if (t.matches('input,select,textarea,button')) {
            t.disabled = false;
            t.readOnly = false;
            t.removeAttribute('aria-disabled');
            t.style.pointerEvents = 'auto';
            t.tabIndex = 0;
            console.log('[focusin] en', t.id || t.name || t.tagName);
        }
    } catch {}
});

async function cargarPaginas() {
    try {
        const resp = await fetch('controllers/cpag.php', {
            method: 'POST',
            body: new URLSearchParams({ accion: 'listar' })
        });
        const html = await resp.text();
        console.log('[listar] status:', resp.status, 'ok:', resp.ok);
        console.log('[listar] body:', html);
        if (!resp.ok) {
            tablaPaginas.innerHTML = "<tr><td colspan='5'>Error HTTP al cargar páginas (" + resp.status + ")</td></tr>";
            return;
        }
        tablaPaginas.innerHTML = html;
    } catch (err) {
        console.error('Error al listar páginas:', err);
        tablaPaginas.innerHTML = "<tr><td colspan='5'>Error al cargar páginas.</td></tr>";
    }
}

function abrirModalNuevo() {
    formPagina.reset();
    document.getElementById('idpag').value = '';
    document.getElementById('modalTitulo').textContent = 'Nueva Página';
    // Asegura la instancia antes de mostrar
    ensureModal();
    // Garantiza que ningún overlay bloquee la interacción
    overlay.style.display = 'none';
    overlay.style.pointerEvents = 'none';
    sanitizeModalInteractivity();
    modal && modal.show();
    // Forzar foco al primer input tras abrir
    setTimeout(() => {
        const first = document.getElementById('nompag');
        const ruta = document.getElementById('rutpag');
        const sel  = document.getElementById('mospag');
        [first, ruta, sel].forEach(ctrl => {
            if (ctrl) {
                ctrl.disabled = false;
                ctrl.readOnly = false;
                ctrl.removeAttribute('aria-disabled');
                ctrl.tabIndex = 0;
                ctrl.style.pointerEvents = 'auto';
            }
        });
        if (first) first.focus();
        console.log('[post-open] estados:', {
            nom_disable: first?.disabled, nom_readonly: first?.readOnly,
            rut_disable: ruta?.disabled,  rut_readonly: ruta?.readOnly,
            sel_disable: sel?.disabled
        });
        // Si por alguna razón el overlay sigue visible, ocultarlo
        if (overlay && overlay.style.display !== 'none') {
            overlay.style.display = 'none';
            overlay.style.pointerEvents = 'none';
        }
    }, 150);
}

formPagina.addEventListener('submit', async (e) => {
    e.preventDefault();
    // Si el formulario no es válido, mostrar validación nativa y no continuar
    if (!formPagina.checkValidity()) {
        formPagina.reportValidity();
        return;
    }
    // No usar overlay para evitar bloqueos visuales durante pruebas
    console.log("🟢 Evento submit detectado correctamente");

    // Construir cuerpo como application/x-www-form-urlencoded (más compatible)
    const idpag = (document.getElementById('idpag').value || '').trim();
    const nompag = (document.getElementById('nompag').value || '').trim();
    const rutpag = (document.getElementById('rutpag').value || '').trim();
    const mospag = (document.getElementById('mospag').value || '0').trim();
    const accion = idpag ? 'actualizar' : 'insertar';
    const body = new URLSearchParams({ accion, idpag, nompag, rutpag, mospag });

    const controller = new AbortController();
    const timeoutId = setTimeout(() => controller.abort(), 15000); // 15s timeout

    try {
        console.log('[submit] enviando:', Object.fromEntries(body.entries()));
        const resp = await fetch('controllers/cpag.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
            body,
            signal: controller.signal,
            cache: 'no-store',
            keepalive: true,
        });

        const text = await resp.text();
        console.log('🟡 Respuesta cruda:', text);
        console.log('[submit] status:', resp.status, 'ok:', resp.ok);

        let data;
        try {
            data = JSON.parse(text);
        } catch {
            alert('⚠️ Respuesta no válida del servidor. Revisa controllers/cpag.php.');
            // Evita ocultar el modal con el foco dentro; primero suelta el foco
            if (document.activeElement) document.activeElement.blur();
            modalEl.addEventListener('hidden.bs.modal', () => {
                removerBackdrops();
            }, { once: true });
            if (!modal && window.bootstrap && modalEl) {
                modal = bootstrap.Modal.getOrCreateInstance(modalEl);
            }
            modal && modal.hide();
            return;
        }

        alert(data.mensaje || 'Operación realizada.');
        if (document.activeElement) document.activeElement.blur();
        modalEl.addEventListener('hidden.bs.modal', () => {
            removerBackdrops();
        }, { once: true });
        if (!modal && window.bootstrap && modalEl) {
            modal = bootstrap.Modal.getOrCreateInstance(modalEl);
        }
        modal && modal.hide();
        formPagina.reset();
        cargarPaginas();
    } catch (err) {
        if (err.name === 'AbortError') {
            alert('⏱️ El servidor tardó demasiado en responder. Intenta nuevamente.');
        } else {
            alert('Error de conexión o fallo en la petición.');
        }
        console.error(err);
        if (document.activeElement) document.activeElement.blur();
        modalEl.addEventListener('hidden.bs.modal', () => {
            removerBackdrops();
        }, { once: true });
        if (!modal && window.bootstrap && modalEl) {
            modal = bootstrap.Modal.getOrCreateInstance(modalEl);
        }
        modal && modal.hide();
    } finally {
        clearTimeout(timeoutId);
    }
});

function editarPagina(id, nombre, ruta, mostrar) {
    document.getElementById('idpag').value = id;
    document.getElementById('nompag').value = nombre;
    document.getElementById('rutpag').value = ruta;
    document.getElementById('mospag').value = mostrar;
    document.getElementById('modalTitulo').textContent = 'Editar Página';
    ensureModal();
    overlay.style.display = 'none';
    overlay.style.pointerEvents = 'none';
    sanitizeModalInteractivity();
    modal && modal.show();
    setTimeout(() => {
        const first = document.getElementById('nompag');
        const ruta = document.getElementById('rutpag');
        const sel  = document.getElementById('mospag');
        [first, ruta, sel].forEach(ctrl => {
            if (ctrl) {
                ctrl.disabled = false;
                ctrl.readOnly = false;
                ctrl.removeAttribute('aria-disabled');
                ctrl.tabIndex = 0;
                ctrl.style.pointerEvents = 'auto';
            }
        });
        if (first) first.focus();
        console.log('[post-open edit] estados:', {
            nom_disable: first?.disabled, nom_readonly: first?.readOnly,
            rut_disable: ruta?.disabled,  rut_readonly: ruta?.readOnly,
            sel_disable: sel?.disabled
        });
        if (overlay && overlay.style.display !== 'none') {
            overlay.style.display = 'none';
            overlay.style.pointerEvents = 'none';
        }
    }, 150);
}

// Emergencia: tecla ESC esconde overlay y cierra modal si algo queda bloqueado
window.addEventListener('keydown', (ev) => {
    if (ev.key === 'Escape') {
        overlay.style.display = 'none';
        ensureModal();
        if (modal) {
            modalEl.addEventListener('hidden.bs.modal', () => {
                removerBackdrops();
            }, { once: true });
            modal.hide();
        }
    }
});

function removerBackdrops() {
    document.querySelectorAll('.modal-backdrop').forEach(b => b.remove());
    document.body.classList.remove('modal-open');
    document.body.style.removeProperty('overflow');
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
        alert(data.mensaje);
        cargarPaginas();
    } catch (err) {
        alert('Error al eliminar la página.');
    } finally {
        overlay.style.display = 'none';
    }
}
</script>
