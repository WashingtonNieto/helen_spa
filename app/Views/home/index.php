<!-- Banner Principal (Hero) -->
<section class="hero">
    <h2>Bienvenido a Helen Spa</h2>
    <p>Descubre nuestros tratamientos corporales y faciales para renovar tu energía.</p>
    <a href="<?= URL_BASE ?>/agendar" class="btn btn-dorado">Agendar mi Cita</a>
</section>

<!-- Sección de Servicios Destacados -->
<section class="servicios-destacados my-5">
    <div class="text-center mb-4">
        <h3 style="color: var(--verde-oscuro); font-size: 1.8rem;">Nuestros Servicios</h3>
        <p class="text-muted">Diseñados para brindarte la mejor experiencia de bienestar y relajación.</p>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 24px;">
        
        <div class="card-servicio text-center">
            <h4 style="color: var(--verde-oscuro); margin-bottom: 10px;">Masajes Relajantes</h4>
            <p style="font-size: 0.95rem; margin-bottom: 15px;">Terapia corporal diseñada para liberar el estrés acumulado y mejorar la circulación.</p>
            <a href="<?= URL_BASE ?>/agendar" class="btn btn-dorado btn-sm" style="font-size: 0.85rem; padding: 8px 16px;">Agendar</a>
        </div>

        <div class="card-servicio text-center">
            <h4 style="color: var(--verde-oscuro); margin-bottom: 10px;">Limpieza Facial Profunda</h4>
            <p style="font-size: 0.95rem; margin-bottom: 15px;">Tratamiento de hidratación y exfoliación para devolver el brillo natural a tu piel.</p>
            <a href="<?= URL_BASE ?>/agendar" class="btn btn-dorado btn-sm" style="font-size: 0.85rem; padding: 8px 16px;">Agendar</a>
        </div>

        <div class="card-servicio text-center">
            <h4 style="color: var(--verde-oscuro); margin-bottom: 10px;">Chocolaterapia</h4>
            <p style="font-size: 0.95rem; margin-bottom: 15px;">Envoltura corporal nutritiva e hidratante a base de cacao puro y aceites esenciales.</p>
            <a href="<?= URL_BASE ?>/agendar" class="btn btn-dorado btn-sm" style="font-size: 0.85rem; padding: 8px 16px;">Agendar</a>
        </div>

    </div>
</section>