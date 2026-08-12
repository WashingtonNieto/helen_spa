<section class="section my-5">
  <div class="container d-flex justify-content-center">
    <div class="card shadow-sm p-4" style="max-width: 420px; width: 100%; border-radius: 10px;">
      
      <div class="text-center mb-4">
        <h2 style="color: var(--verde-oscuro, #2a4d38);">Acceso Administrativo</h2>
        <p class="text-muted">Ingresa tus credenciales para gestionar Helen Spa</p>
      </div>

      <?php if (!empty($error)): ?>
        <div class="alert alert-danger p-2 text-center" style="background: #f8d7da; color: #842029; border-radius: 6px;">
          <?= htmlspecialchars($error) ?>
        </div>
      <?php endif; ?>

      <form action="/helen_spa_php/login/procesar" method="post">
        
        <div class="mb-3">
          <label for="email" class="form-label">Correo Electrónico</label>
          <input type="email" id="email" name="email" class="form-control" placeholder="admin@helenspa.com" required autofocus>
        </div>

        <div class="mb-3">
          <label for="password" class="form-label">Contraseña</label>
          <input type="password" id="password" name="password" class="form-control" placeholder="••••••••" required>
        </div>

        <button type="submit" class="btn w-100" style="background: var(--verde-oscuro, #2a4d38); color: #fff; padding: 10px; border-radius: 6px;">
          Iniciar Sesión
        </button>

      </form>
    </div>
  </div>
</section>