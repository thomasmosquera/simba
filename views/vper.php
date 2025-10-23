<div class="container-fluid px-4 py-5">
    <div class="text-center mb-5">
        <h1 class="display-5 fw-bold text-warning">Gestión de perfiles SIMBA</h1>
        <p class="lead">
            Bienvenido, <span class="text-warning">
                <?= isset($_SESSION['nomusu']) ? strtoupper($_SESSION['nomusu']) : 'Invitado' ?>
            </span>. Aquí tienes el módulo de gestión de perfiles.
        </p>
        <?php if (isset($_GET['mensaje'])): ?>
            <div class="alert alert-success mt-3 text-center">
                <?= htmlspecialchars($_GET['mensaje']) ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Formulario de creación/edición -->
    <div class="container-fluid px-4 py-5">
        <div class="card bg-secondary text-white shadow-lg p-4">
            <div class="card-body">
                <?php 
                $is_edit = (isset($_GET['ope']) && $_GET['ope'] == 'eDi');
                $action_url = "index.php?pg=" . $pg . "&ope=" . ($is_edit ? 'actedit' : 'crear'); 
                $form_title = $is_edit ? 'Edición de Perfil' : 'Creación de Perfiles SIMBA';
                $form_icon = $is_edit ? 'fa-user-edit' : 'fa-user-plus';
                
                $edit_idpef   = $is_edit && isset($dtOne) ? htmlspecialchars($dtOne["idpef"]) : '';
                $edit_nompef  = $is_edit && isset($dtOne) ? htmlspecialchars($dtOne["nompef"]) : '';
                $edit_pgini   = $is_edit && isset($dtOne) ? htmlspecialchars($dtOne["pgini"]) : '';
                ?>
                
                <h3><i class="fa-solid <?= $form_icon ?> text-warning"></i> <?= $form_title ?></h3>
                
                <form action="<?= $action_url ?>" method="POST"> 
                    <?php if ($is_edit): ?>
                        <input type="hidden" name="idpef" value="<?= $edit_idpef ?>">
                    <?php endif; ?>
                    
                    <div class="row">
                        <div class="col-md-<?= $is_edit ? '6' : '12' ?>">
                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre del Perfil</label>
                                <input type="text" id="nombre" name="nombre" class="form-control"
                                       placeholder="ADMINISTRADOR, CLIENTE, CUIDADOR..." 
                                       value="<?= $edit_nompef ?>" required>
                            </div>
                        </div>
                        <?php if ($is_edit): ?>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="id_perfil_display" class="form-label">ID Perfil</label>
                                <input type="text" id="id_perfil_display" class="form-control bg-dark text-white" 
                                       value="<?= $edit_idpef ?>" disabled>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="pgini" class="form-label">Página inicial del perfil (ID de página)</label>
                        <input type="number" id="pgini" name="pgini" class="form-control" 
                               value="<?= $edit_pgini ?>" placeholder="1001, 1002, 1003..." required>
                    </div>

                    <button class="btn btn-warning"><?= $is_edit ? 'Actualizar perfil' : 'Crear perfil' ?></button>
                    <?php if ($is_edit): ?>
                        <a href="index.php?pg=<?= $pg ?>" class="btn btn-outline-dark ms-2">Cancelar</a>
                    <?php endif; ?>
                    
                    <?php if (!empty($alerta)): ?>
                        <div class="alert alert-danger alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3 shadow-lg" 
                            role="alert" 
                            style="z-index: 2000; min-width: 350px;">
                            <i class="fa-solid fa-triangle-exclamation me-2"></i>
                            <?= htmlspecialchars($alerta) ?>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>

                        <script>
                            // Cierre automático en 4 segundos
                            setTimeout(() => {
                                const alert = document.querySelector('.alert');
                                if (alert) {
                                    alert.classList.remove('show');
                                    alert.classList.add('fade');
                                    setTimeout(() => alert.remove(), 500);
                                }
                            }, 4000);
                        </script>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>

    <!-- Tabla de perfiles -->
    <div class="container-fluid px-4 py-5">
        <div class="card bg-secondary text-white shadow-lg p-4">
            <div class="card-body">
                <h3><i class="fa-solid fa-user-gear text-warning"></i> Perfiles existentes</h3>
                <table id="example" class="table table-striped table-dark">
                    <thead>
                        <tr>
                            <th>Perfil</th>
                            <th>Ins</th>
                            <th>Act</th>
                            <th>Eli</th>
                            <th>Editar</th>
                            <th>Eliminar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($dtAll)){ 
                            foreach($dtAll as $dt){ ?>
                            <tr>
                                <td>
                                    <?= htmlspecialchars($dt["idpef"]) ?> - <?= htmlspecialchars($dt["nompef"]) ?><br>
                                    <strong>Página Inicial: </strong>
                                    <?= htmlspecialchars($dt["pgini"]) ?> - <?= !empty($dt["nompag"]) ? htmlspecialchars($dt["nompag"]) : "Sin página" ?>
                                </td>
                                
                                <td>
                                    <div class="d-flex justify-content-center">
                                    <?php if($dt["insper"]==1){ ?>
                                        <a href="index.php?pg=<?=$pg;?>&ope=act&insper=2&idpef=<?=$dt["idpef"];?>" title="Desactivar Inserción" class="btn btn-outline-success rounded p-2 d-flex justify-content-center align-items-center">
                                            <i class="fa-solid fa-circle-check fs-4"></i>
                                        </a>
                                    <?php } else { ?>
                                        <a href="index.php?pg=<?=$pg;?>&ope=act&insper=1&idpef=<?=$dt["idpef"];?>" title="Activar Inserción" class="btn btn-outline-secondary rounded p-2 d-flex justify-content-center align-items-center">
                                            <i class="fa-solid fa-circle-xmark fs-4"></i>
                                        </a>
                                    <?php } ?>
                                    </div>
                                </td>

                                <td>
                                    <div class="d-flex justify-content-center">
                                    <?php if($dt["updper"]==1){ ?>
                                        <a href="index.php?pg=<?=$pg;?>&ope=act&updper=2&idpef=<?=$dt["idpef"];?>" title="Desactivar Actualización" class="btn btn-outline-success rounded p-2 d-flex justify-content-center align-items-center">
                                            <i class="fa-solid fa-circle-check fs-4"></i>
                                        </a>
                                    <?php } else { ?>
                                        <a href="index.php?pg=<?=$pg;?>&ope=act&updper=1&idpef=<?=$dt["idpef"];?>" title="Activar Actualización" class="btn btn-outline-secondary rounded p-2 d-flex justify-content-center align-items-center">
                                            <i class="fa-solid fa-circle-xmark fs-4"></i>
                                        </a>
                                    <?php } ?>
                                    </div>
                                </td>

                                <td>
                                    <div class="d-flex justify-content-center flex-wrap">
                                    <?php if($dt["delper"]==1){ ?>
                                        <a href="index.php?pg=<?=$pg;?>&ope=act&delper=2&idpef=<?=$dt["idpef"];?>" title="Desactivar Eliminación" class="btn btn-outline-success rounded p-2 d-flex justify-content-center align-items-center mb-1">
                                            <i class="fa-solid fa-circle-check fs-4"></i>
                                        </a>
                                    <?php } else { ?>
                                        <a href="index.php?pg=<?=$pg;?>&ope=act&delper=1&idpef=<?=$dt["idpef"];?>" title="Activar Eliminación" class="btn btn-outline-secondary rounded p-2 d-flex justify-content-center align-items-center mb-1">
                                            <i class="fa-solid fa-circle-xmark fs-4"></i>
                                        </a>
                                    <?php } ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center">
                                        <a href="index.php?pg=<?=$pg; ?>&ope=eDi&idpef=<?=$dt["idpef"];?>" title="Editar perfil" class="btn btn-outline-warning rounded p-2 d-flex justify-content-center align-items-center">
                                            <i class="fa-solid fa-pen-to-square fs-4"></i>
                                        </a>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center">
                                        <?php $dtcon = $mper->getOneCon($dt["idpef"]); 
                                        if ($dtcon && $dtcon["con"] == 0) { ?>
                                            <a href="index.php?pg=<?=$pg; ?>&ope=eLi&idpef=<?=$dt["idpef"];?>" title="Eliminar perfil" onclick="return confirm('¿Seguro que desea eliminar este perfil?');" class="btn btn-outline-danger rounded p-2 d-flex justify-content-center align-items-center">
                                                <i class="fa-solid fa-trash fs-4"></i>
                                            </a>
                                        <?php } else { ?>
                                            <span class="text-secondary">Acción no permitida</span>
                                        <?php } ?>
                                    </div>
                                </td>
                            </tr>
                        <?php }} ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
