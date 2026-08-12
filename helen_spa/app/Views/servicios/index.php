<div class="container my-5">
    <div class="section-title text-center mb-4">
        <h2 style="color: var(--verde-oscuro);">Nuestros Servicios</h2>
        <p class="text-muted">Conoce nuestro catálogo completo de tratamientos y servicios de relajación.</p>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 24px;">
        <?php if (!empty($servicios)): ?>
            <?php foreach ($servicios as $servicio): ?>
                <div class="card-servicio text-center">
                    <h3 style="color: var(--verde-oscuro); margin-bottom: 10px;"><?= e($servicio['nombre']) ?></h3>
                    <p style="font-size: 0.95rem; margin-bottom: 15px;"><?= e($servicio['descripcion']) ?></p>
                    <p><strong>Precio:</strong> <?= e(formatear_moneda($servicio['precio'])) ?></p>
                    <p class="text-muted" style="font-size: 0.85rem;">Duración: <?= e($servicio['duracion_min']) ?> min</p>
                    <a href="<?= URL_BASE ?>/agendar" class="btn btn-dorado btn-sm mt-2" style="padding: 8px 16px;">Agendar Cita</a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-center text-muted" style="grid-column: 1 / -1;">No hay servicios registrados en este momento.</p>
        <?php endif; ?>
    </div>
</div>