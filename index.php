<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Cineplanet</title>
  <link rel="icon" type="icon/png" href="/media/images-removebg-preview.png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    
    .carousel-item img {
      width: 100%;
      object-fit: cover;
    }

.card {
  border: none !important;
}

.card img {
  height: 450px;
  object-fit: contain;
  
}

.col {
  margin-top: 40px;
}

.carousel-caption {
  position: absolute;
  top: 50%;
  left: 13%;
  transform: translateY(-50%);
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  justify-content: center;
  text-align: left;
}

h3 {
  font-size: 64px;
  font-weight: 900;
  font-family: sans-serif;
  padding: 10px;
  margin: 0;
  padding: 0;

}

.carousel-caption h3,
.carousel-caption p {
  margin: 0;
  color: white; 
  text-align: left;
}

.carousel-caption p {
  font-family: sans-serif;
  font-size: 18px;
  margin: 32px 0;
  text-shadow: 2px 2px 4px rgba(0,0,0,0.8);
}

.red-title {
  background-color: #E50246;
  padding: 0 10px;
  
}

  </style> 
</head>
<body class="inicio">

<?php
if (session_status() === PHP_SESSION_NONE) {
  
    session_start();
    
}

include 'includes/header.php';

$mostrarModalCumple = false;

if (isset($_SESSION['usuario'])) {
    $usuario = $_SESSION['usuario'];
    $hoy = date('m-d');
    $cumple = date('m-d', strtotime($usuario['fecha_nacimiento']));

    if ($hoy === $cumple) {
        $mostrarModalCumple = true;
    }
}

?>

<div id="carouselNovedades" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
  <div class="carousel-inner">
    
    <div class="carousel-item active">
      <img src="/media/banner_web_desktop_1920x1080 (1).jpg" alt="Banner">
      <div class="carousel-caption">
        <h3><span class="red-title">Elio</span></h3>
        <p>Elio es un niño con una gran obsesión por los alienígenas,<br> descubre la respuesta a esa pregunta cuando es transportado al<br> Comuniverso</p>
        <a href="/seleccionar_butacas.php?id=10&titulo=Elio&poster=%2Fmedia%2Fportadas%2Fportada_elio-disney-presenta_disney_202502051650.webp" class="btn btn-primary">Comprar</a>
      </div>
    </div>

    <div class="carousel-item">
      <img src="/media/banner_web_desktop_1920x1080 (5).jpg" alt="Banner">
      <div class="carousel-caption">
        <h3><span class="red-title">Exterminio</span><br><span>la evolución</span></h3>
        <p>Trama no develada. Tercera película de la franquicia<br>"28 días después".</p>
        <a href="/seleccionar_butacas.php?id=2&titulo=Exterminio%3A%20Evoluci%C3%B3n&poster=%2Fmedia%2Fportadas%2FEXT_1400x2100.jpg" class="btn btn-primary">Comprar</a>
      </div>
    </div>

    <div class="carousel-item">
      <img src="/media/banner_web_desktop_1920x1080 (2).jpg" alt="Banner">
      <div class="carousel-caption">
        <h3><span class="red-title">Como</span><br><span>Entrenar a Tu<br> dragón</span></h3>
        <p>Un joven vikingo aspira a cazar dragones, pero se convierte<br> inesperadamente en el amigo de un dragón.</p>
        <a href="/seleccionar_butacas.php?id=9&titulo=Cómo%20Entrenar%20a%20tu%20Dragón&poster=%2Fmedia%2Fportadas%2FMV5BY2E5YjE2YTQtZGIxYi00YTU4LTk3YzItYmMyZTM3M2I5ZTM3XkEyXkFqcGc%40._V1_FMjpg_UX1000_.jpg" class="btn btn-primary">Comprar</a>
      </div>
    </div>

    <div class="carousel-item">
      <img src="/media/banner_web_desktop_1920x1080 (6).jpg" alt="Banner">
      <div class="carousel-caption">
        <h3><span class="red-title">Super</span><span>man</span></h3>
        <p>La película explora el viaje de Superman para reconciliar su herencia alienígena<br> con su familia humana adoptiva.</p>
        <a href="/seleccionar_butacas.php?id=11&titulo=SUPERMAN%3A%20LEGACY&poster=%2Fmedia%2Fportadas%2Fvdsy6x3a8jtd1.webp" class="btn btn-primary">Comprar</a>
      </div>
    </div>

  </div>

  <button class="carousel-control-prev" type="button" data-bs-target="#carouselNovedades" data-bs-slide="prev">
    <span class="carousel-control-prev-icon"></span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#carouselNovedades" data-bs-slide="next">
    <span class="carousel-control-next-icon"></span>
  </button>
</div>
 
  <section class="container mt-5">
    <h2 class="mb-4">En cartelera</h2>
    <div class="row row-cols-1 row-cols-md-3 g-4">
        <div class="col">
            <div class="card movie-card">
              <img src="https://cdn.apis.cineplanet.com.pe/CDN/media/entity/get/FilmPosterGraphic/HO00002252?referenceScheme=HeadOffice&allowPlaceHolder=true">
               <div class="card-body d-flex justify-content-center">
                <a href="http://localhost:3000/seleccionar_butacas.php?id=8&titulo=THUNDERBOLTS&poster=%2Fmedia%2Fportadas%2F1744752722.jpg" class="btn btn-primary w-100">Comprar</a>

              </div>
            </div>
          </div>

          <div class="col">
            <div class="card movie-card">
              <img src="https://cdn.apis.cineplanet.com.pe/CDN/media/entity/get/FilmPosterGraphic/HO00002390?referenceScheme=HeadOffice&allowPlaceHolder=true" class="card-img-top" alt="...">
              <div class="card-body d-flex justify-content-center">
                <a href="/seleccionar_butacas.php?id=5&titulo=DESTINO%20FINAL%20LAZOS%20DE%20SANGRE&poster=%2Fmedia%2Fportadas%2F1746626561.png" class="btn btn-primary w-100">Comprar</a>

              </div>
            </div>
          </div>

      <div class="col">
        <div class="card movie-card">
          <img src="https://cdn.apis.cineplanet.com.pe/CDN/media/entity/get/FilmPosterGraphic/HO00002413?referenceScheme=HeadOffice&allowPlaceHolder=true" class="card-img-top" alt="...">
          <div class="card-body d-flex justify-content-center">
            <a href="/seleccionar_butacas.php?id=1&titulo=ARMAGEDÓN%3A%20DIOSES%20DEL%20APOCALIPSIS&poster=%2Fmedia%2Fportadas%2FHO00002413.jpg" class="btn btn-primary w-100">Comprar</a>

          </div>
        </div>
      </div>
      
    </div>
  </section>

<?php if ($mostrarModalCumple): ?>

<div id="cumple-overlay" class="position-fixed top-0 start-0 w-100 h-100 bg-dark bg-opacity-75" style="z-index: 1050;"></div>

<div id="cumple-notification" class="position-fixed top-50 start-50 translate-middle p-4 bg-warning shadow-lg rounded-4 text-dark text-center animate__animated animate__zoomIn" style="z-index: 1055; max-width: 90%; width: 400px;">
  <img src="/media/4c78970ece656a2ead941a86f03e4e86-pastel-de-cumpleanos-removebg-preview.png" alt="Regalo" width="60" class="mb-3">
  <h4 class="fw-bold mb-2">¡Feliz cumpleaños, socio!</h4>
  <p class="mb-3">Tienes una <strong>entrada 2D totalmente gratis</strong> válida hasta 3 días desde hoy. Preséntate con tu DNI en taquilla. 🎟️</p>
  <button class="btn btn-dark btn-sm px-4 rounded-pill" onclick="cerrarCumple()">Entendido</button>
</div>

<script>
function cerrarCumple() {
    document.getElementById('cumple-notification').remove();
    document.getElementById('cumple-overlay').remove();
}
</script>
<?php endif; ?>

    <footer class="bg-dark text-light mt-5 pt-4 pb-3">
  <div class="container">
    <div class="row align-items-center mb-3">
      <div class="col-md-6">
        <h5 class="fw-bold mb-1">CinePlanet</h5>
        <p class="mb-0">Vive la experiencia del cine como nunca antes.</p>
      </div>
      <div class="col-md-6 text-md-end mt-3 mt-md-0">
        <a href="https://cdnpe.cineplanet.com.pe/assets/1862f49f-807a-44cc-bf34-629d915042e7" class="text-light me-3 text-decoration-none">Términos</a>
        <a href="https://cdnpe.cineplanet.com.pe/assets/0b1cdd88-83a2-48b7-820b-8bac386ced30" class="text-light me-3 text-decoration-none">Política</a>
        <a href="/soporte.php" class="text-light text-decoration-none">Soporte</a>
      </div>
    </div>
    <div class="row border-top pt-3">
      <div class="col-md-6">
        <small>&copy; 2025 CinePlanet. Todos los derechos reservados.</small>
      </div>
      <div class="col-md-6 text-md-end">
        <a href="https://www.facebook.com/cineplanet/?locale=es_LA" target="_blank" class="me-2">
          <img src="https://cdn-icons-png.flaticon.com/24/733/733547.png" alt="Facebook" width="24" height="24">
        </a>
        <a href="https://x.com/cineplanet" target="_blank" class="me-2">
          <img src="https://cdn-icons-png.flaticon.com/24/733/733579.png" alt="Twitter" width="24" height="24">
        </a>
        <a href="https://www.instagram.com/cineplanetoficial/?hl=es" target="_blank">
          <img src="https://cdn-icons-png.flaticon.com/24/2111/2111463.png" alt="Instagram" width="24" height="24">
        </a>
      </div>
    </div>
  </div>
</footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
  const navbar = document.querySelector('.navbar');
  const esIndex = window.location.pathname.endsWith('index.php') || window.location.pathname === '/';

  if (esIndex) {
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

</body>
</html>
