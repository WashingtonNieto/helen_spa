<section class="section">
  <div class="container">
    <div class="section-title">
      <h2>Agenda tu Cita</h2>
      <p>Elige el servicio de tu preferencia y selecciona la fecha ideal para relajarte.</p>
    </div>

    <div class="form-agendamiento" style="max-width: 600px; margin: 0 auto;">
      
      <?php if (!empty($errores)): ?>
        <div class="alert alert-danger" style="background:#f8d7da; color:#842029; padding:12px; border-radius:6px; margin-bottom:16px;">
          <ul style="margin:0; padding-left:20px;">
            <?php foreach ($errores as $error): ?>
              <li><?= e($error) ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>

      <form action="/helen_spa_php/agendar/guardar" method="post">
        
        <div class="campo mb-3">
          <label for="nombre">Nombre Completo *</label>
          <input type="text" id="nombre" name="nombre" class="form-control" placeholder="Ej: María Pérez" value="<?= e($datos_viejos['nombre'] ?? '') ?>" required>
        </div>

        <div class="campo mb-3">
          <label for="telefono">Teléfono (WhatsApp) *</label>
          <input type="tel" id="telefono" name="telefono" class="form-control" placeholder="Ej: 3001234567" value="<?= e($datos_viejos['telefono'] ?? '') ?>" required>
        </div>

        <div class="campo mb-3">
          <label for="email">Correo Electrónico (Opcional)</label>
          <input type="email" id="email" name="email" class="form-control" placeholder="correo@ejemplo.com" value="<?= e($datos_viejos['email'] ?? '') ?>">
        </div>

        <div class="campo mb-3">
          <label for="servicio_id">Servicio *</label>
          <select id="servicio_id" name="servicio_id" class="form-control" required>
            <option value="">-- Selecciona un Servicio --</option>
            <?php foreach ($servicios as $servicio): ?>
              <option value="<?= $servicio['id'] ?>" <?= (isset($datos_viejos['servicio_id']) && $datos_viejos['servicio_id'] == $servicio['id']) ? 'selected' : '' ?>>
                <?= e($servicio['nombre']) ?> - $<?= number_format($servicio['precio'], 0, ',', '.') ?> (<?= $servicio['duracion_min'] ?> min)
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="row">
          <div class="col-md-6 campo mb-3">
            <label for="fecha">Fecha *</label>
            <input type="date" id="fecha" name="fecha" class="form-control" min="<?= date('Y-m-d') ?>" value="<?= e($datos_viejos['fecha'] ?? '') ?>" required>
          </div>

          <div class="col-md-6 campo mb-3">
            <label for="hora">Hora *</label>
            <input type="time" id="hora" name="hora" class="form-control" value="<?= e($datos_viejos['hora'] ?? '') ?>" required>
          </div>
        </div>

        <button type="submit" class="btn btn-dorado w-100 mt-3" style="background: var(--verde-oscuro); color: #fff; border: none; padding: 12px;">
          Confirmar Agendamiento
        </button>

      </form>
    </div>
  </div>
</section>