<?php
?>
<div class="container-fluid px-4 py-5">
    <div class="text-center mb-5">
        <img src="img/logo1.png" alt="Logo SIMBA" class="logo-simba mb-3">
        <h1 class="display-5 fw-bold text-warning">Bienvenido a SIMBA</h1>
        <p class="lead text-white">
            Hola, <span class="text-warning">
                <?= isset($_SESSION['nomusu']) ? strtoupper($_SESSION['nomusu']) : 'Invitado' ?>
            </span>.
            Tu perfil es: <span class="text-info">
                <?= isset($_SESSION['tipo_perfil']) ? strtoupper($_SESSION['tipo_perfil']) : 'No Logueado' ?>
            </span>.
        </p>
        <p class="text-white-50">
            Aquí puedes encontrar información relevante y acceder a las funcionalidades permitidas para tu perfil.
        </p>
    </div>

    <div class="row row-cols-1 row-cols-md-3 g-4 text-center">
        <?php if (isset($_SESSION['tipo_perfil'])): ?>
            <?php if ($_SESSION['tipo_perfil'] == 'ADMIN'): ?>
                <div class="col">
                    <div class="card bg-info text-dark shadow-lg h-100">
                        <div class="card-body">
                            <i class="fas fa-chart-line fa-3x mb-3"></i>
                            <h5 class="card-title">Estadísticas del Sistema</h5>
                            <p class="card-text">Accede a reportes y análisis completos.</p>
                            <a href="index.php?pg=admin_page" class="btn btn-dark mt-auto">Ir a Estadísticas</a>
                        </div>
                    </div>
                </div>
            <?php elseif ($_SESSION['tipo_perfil'] == 'CLIENTE'): ?>
                <div class="col">
                    <div class="card bg-primary text-white shadow-lg h-100">
                        <div class="card-body">
                            <i class="fas fa-calendar-check fa-3x mb-3"></i>
                            <h5 class="card-title">Ver Mis Reservas</h5>
                            <p class="card-text">Gestiona tus citas y servicios programados.</p>
                            <a href="index.php?pg=client_page" class="btn btn-light mt-auto">Mis Reservas</a>
                        </div>
                    </div>
                </div>
            <?php elseif ($_SESSION['tipo_perfil'] == 'EMPLEADO'):?>
                <div class="col">
                    <div class="card bg-success text-white shadow-lg h-100">
                        <div class="card-body">
                            <i class="fas fa-clipboard-check fa-3x mb-3"></i>
                            <h5 class="card-title">Mis Tareas Asignadas</h5>
                            <p class="card-text">Revisa y actualiza tus tareas de cuidado.</p>
                            <a href="index.php?pg=caregiver_page" class="btn btn-light mt-auto">Ver Tareas</a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="col-12 text-center">
                <p class="text-white-50">Inicia sesión para acceder a las funcionalidades del sistema.</p>
            </div>
        <?php endif; ?>
    </div>
</div>