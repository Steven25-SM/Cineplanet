<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
$esAdmin = isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin';
?>

<nav class="navbar navbar-expand-lg navbar-dark fixed-top <?php echo $esAdmin ? 'navbar-admin' : ''; ?>">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center" href="index.php">
      <img src="media/logo1.png" alt="Logo" width="70" height="70" class="me-2">
      cineplanet
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse justify-content-between" id="navbarNav">
      <div class="mx-auto">
        <ul class="navbar-nav">
  <?php
    $paginaActual = basename($_SERVER['PHP_SELF']);
    function navItem($archivo, $nombre) {
      global $paginaActual;
      $activo = $paginaActual === $archivo ? 'active' : '';
      echo "<li class='nav-item'><a class='nav-link $activo' href='$archivo'>$nombre</a></li>";
    }

    navItem('index.php', 'Inicio');
    navItem('cartelera.php', 'Cartelera');
    navItem('promociones.php', 'Promociones');
    navItem('socios.php', 'Socios');
    navItem('solicitar_sala.php', 'Eventos');
  ?>
</ul>

      </div>

      <?php if (isset($_SESSION['dni'])): ?>
        <div class="dropdown">
          <button class="btn btn-light dropdown-toggle d-flex align-items-center" type="button" id="socioDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="padding: 0; background: none; border: none;">
            <img
              src="<?= isset($_SESSION['imagen']) && $_SESSION['imagen'] ? 'uploads/' . htmlspecialchars($_SESSION['imagen']) : 'https://www.w3schools.com/howto/img_avatar.png' ?>"
              alt="Perfil"
              style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover;">
          </button>
          <ul class="dropdown-menu dropdown-menu-end py-2" aria-labelledby="socioDropdown">
            <li>
              <a class="dropdown-item custom-item" href="perfil.php">👤 Ver perfil</a>
            </li>

            <?php if ($esAdmin): ?>
              <li>
                <hr class="dropdown-divider my-1">
              </li>
              <li>
                <a class="dropdown-item admin-link custom-item text-center" href="admin.php">
                  <strong>Panel Admin</strong>
                </a>
              </li>
            <?php endif; ?>

            <li>
              <hr class="dropdown-divider my-1">
            </li>
            <li>
              <a class="dropdown-item custom-item" href="logout.php">🚪 Cerrar sesión</a>
            </li>
          </ul>


        </div>
      <?php else: ?>
        <div class="d-flex align-items-center gap-2">
          <a href="login.php" class="btn btn-light">Iniciar sesión</a>
          <a href="register.php" class="btn btn-warning text-dark ms-1">¿Aún no eres socio?</a>
        </div>

      <?php endif; ?>
    </div>
  </div>
</nav>

<script>
  const navbar = document.querySelector('.navbar');
  const esIndex = window.location.pathname.endsWith('index.php') || window.location.pathname === '/';

  if (esIndex && !navbar.classList.contains('navbar-admin')) {
    navbar.classList.add('inicio');
    window.addEventListener('scroll', () => {
      if (window.scrollY > 10) {
        navbar.classList.add('scrolled');
      } else {
        navbar.classList.remove('scrolled');
      }
    });
  } else {
    navbar.classList.add('fondo-fijo');
  }
</script>

<style>
  .navbar li {
    font-size: 18px;
    padding: 8px;
  }

  .navbar {
    transition: background-color 0.3s ease;
    border-bottom: 1px solid white;
    padding-top: 0.5rem;
    padding-bottom: 0.5rem;
  }

  .nav-link,
  .navbar .btn {
    font-weight: bold;
  }

  .inicio {
    background-color: rgba(0, 0, 0, 0.3);
  }

  .inicio.scrolled {
    background-color: rgb(8, 39, 85) !important;
    border: none;
  }

  .fondo-fijo {
    background-color: rgb(8, 39, 85);
  }

  .navbar-admin {
    background-color: #b30000 !important;
    border-bottom: 3px solid white;
  }

  body {
    background-color: white !important;
  }

  .dropdown-menu .dropdown-item {
    font-size: 14px;
    padding-top: 6px !important;
    padding-bottom: 6px !important;
    transition: all 0.2s ease;
  }

  .admin-link {
    color: white !important;
    background-color: #b30000;
    border-radius: 6px;
    font-weight: bold;
  }

  .admin-link:hover {
    background-color: #8b0000;
    transform: scale(1.03);
  }

  .custom-item {
    font-size: 15.5px !important;
    font-weight: bold !important;
    padding-top: 6px !important;
    padding-bottom: 6px !important;
  }

  .admin-link {
    color: white !important;
    background-color: #b30000;
    border-radius: 6px;
    transition: all 0.2s ease;
  }

  .admin-link:hover {
    background-color: #8b0000;
    transform: scale(1.03);
  }
</style>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>