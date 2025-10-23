
<div class="container-fluid px-4 py-5">
        <div class="text-center mb-5">

            <h1 class="display-5 fw-bold text-warning">Generación de reportes SIMBA</h1>
            <p class="lead">
                Bienvenido, <span class="text-warning">
                    <?= isset($_SESSION['nomusu']) ? strtoupper($_SESSION['nomusu']) : 'Invitado' ?>
                </span>. Aquí tienes el formulario de reportes.
            </p>
        </div>

        <div class="container-fluid px-4 py-5">
            <div class="card bg-secondary text-white shadow-lg p-4">
                <div class="card-body">
                    <form action="index.php?pg=1004&ope=generar" method="get">
                        
                        <div class="mb-3">
                            <label for="desde" class="form-label">Fecha desde</label>
                            <input type="date" id="desde" name="desde" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label for="hasta" class="form-label">Fecha hasta</label>
                            <input type="date" id="hasta" name="hasta" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label for="estado" class="form-label">Estado de la Reserva</label>
                            <select id="estado" name="estado" class="form-select bg-dark text-light">
                                <option value="">Todos</option>
                                <option value="pendiente">Pendiente</option>
                                <option value="realizado">Realizado</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="rol" class="form-label">Tipo de Usuario</label>
                            <select id="rol" name="rol" class="form-select bg-dark text-light">
                                <option value="">Todos</option>
                                <option value="cliente">Cliente</option>
                                <option value="cuidador">Cuidador</option>
                            </select>
                        </div>
                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn btn-warning btn-sm">Visualizar Reporte</button>
                            <button type="submit" class="btn btn-warning btn-sm">Descargar PDF</button>
                            <button type="submit" class="btn btn-warning btn-sm">Descargar Excel</button>
                        </div>
                    </form>

                    <?php if (!empty($alerta)): ?>
                        <div class="alert alert-danger mt-4 text-center">
                            <?= $alerta ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>