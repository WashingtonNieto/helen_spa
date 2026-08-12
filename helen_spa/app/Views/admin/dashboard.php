<div class="container-fluid my-4 px-4">
  
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Panel de Administración - Helen Spa</h2>
    <span class="text-muted">Hola, <strong><?= e($_SESSION['usuario_nombre'] ?? 'Administrador') ?></strong> | <a href="/helen_spa_php/logout" class="text-danger">Cerrar Sesión</a></span>
  </div>

  <?php if (!empty($mensaje)): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <?= e($mensaje) ?>
    </div>
  <?php endif; ?>

  <!-- Tarjetas de Métricas (KPIs) -->
  <div class="row g-3 mb-4">
    <div class="col-md-4">
      <div class="card border-0 shadow-sm p-3 bg-white rounded">
        <h6 class="text-muted">Citas para Hoy</h6>
        <h3 class="mb-0 text-primary"><?= $stats['citas_hoy'] ?></h3>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card border-0 shadow-sm p-3 bg-white rounded">
        <h6 class="text-muted">Pendientes de Confirmar</h6>
        <h3 class="mb-0 text-warning"><?= $stats['pendientes'] ?></h3>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card border-0 shadow-sm p-3 bg-white rounded">
        <h6 class="text-muted">Citas Confirmadas</h6>
        <h3 class="mb-0 text-success"><?= $stats['confirmadas'] ?></h3>
      </div>
    </div>
  </div>

  <!-- Filtros y Tabla -->
  <div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
      <h5 class="mb-0">Gestión de Citas</h5>
      
      <!-- Filtros -->
      <div class="btn-group btn-group-sm">
        <a href="/helen_spa_php/admin/dashboard" class="btn btn-outline-secondary <?= empty($filtro) ? 'active' : '' ?>">Todas</a>
        <a href="/helen_spa_php/admin/dashboard?estado=pendiente" class="btn btn-outline-warning <?= $filtro === 'pendiente' ? 'active' : '' ?>">Pendientes</a>
        <a href="/helen_spa_php/admin/dashboard?estado=confirmada" class="btn btn-outline-success <?= $filtro === 'confirmada' ? 'active' : '' ?>">Confirmadas</a>
      </div>
    </div>

    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th>#</th>
              <th>Cliente</th>
              <th>Teléfono</th>
              <th>Servicio</th>
              <th>Fecha y Hora</th>
              <th>Estado</th>
              <th class="text-end">Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($citas)): ?>
              <?php foreach ($citas as $cita): ?>
                <tr>
                  <td><strong>#<?= $cita['id'] ?></strong></td>
                  <td><?= e($cita['cliente_nombre'] ?? $cita['nombre'] ?? 'Cliente') ?></td>
                  <td><?= e($cita['telefono']) ?></td>
                  <td><?= e($cita['servicio_nombre']) ?></td>
                  <td>
                    <?= e(formatear_fecha_es($cita['fecha'])) ?><br>
                    <small class="text-muted"><?= e(formatear_hora_es($cita['hora'])) ?></small>
                  </td>
                  <td>
                    <?php if ($cita['estado'] === 'confirmada'): ?>
                      <span class="badge bg-success">Confirmada</span>
                    <?php elseif ($cita['estado'] === 'cancelada'): ?>
                      <span class="badge bg-danger">Cancelada</span>
                    <?php else: ?>
                      <span class="badge bg-warning text-dark">Pendiente</span>
                    <?php endif; ?>
                  </td>
                  <td class="text-end">
                    <form action="/helen_spa_php/admin/citas/estado" method="post" class="d-inline">
                      <input type="hidden" name="cita_id" value="<?= $cita['id'] ?>">
                      
                      <?php if ($cita['estado'] !== 'confirmada'): ?>
                        <button type="submit" name="estado" value="confirmada" class="btn btn-sm btn-outline-success" title="Confirmar Cita">
                          Confirmar
                        </button>
                      <?php endif; ?>

                      <?php if ($cita['estado'] !== 'cancelada'): ?>
                        <button type="submit" name="estado" value="cancelada" class="btn btn-sm btn-outline-danger" title="Cancelar Cita">
                          Cancelar
                        </button>
                      <?php endif; ?>
                    </form>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="7" class="text-center py-4 text-muted">No se encontraron citas con los criterios seleccionados.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

</div>