<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Cartelera - Cineplanet</title>
  <link rel="icon" type="image/png" href="media/logo1.png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
<style>
  body {
    background-color: #f8f9fa;
    margin: 40px;
    font-family: 'Segoe UI', sans-serif;
  }

  .card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border-radius: 12px;
    box-shadow: 0 0 10px rgba(0,0,0,0.05);
    background-color: #ffffff;
  }

  .card:hover {
    transform: scale(1.03);
    box-shadow: 0 10px 20px rgba(8, 39, 85, 0.25);
    filter: brightness(1.05);
    font-weight: 600;
  }

  .mb-3 label {
    border-bottom: 1px solid #0d6efd;
    color: #082755;
    font-weight: 600;
  }

  .col-md-9 {
    margin-left: 0px !important;
  }

  .col-md-3 {
    border-right: 3px solid #082755;
    margin-top: 96px;
  }

  #peliculas-container {
    margin-top: 96px;
  }

  .card-title {
    font-weight: 900;
    font-size: 26px;
    color: #082755;
  }

  

  .card-text {
    font-size: 17px;
    color: #333;
  }

  .filter-text {
    font-size: 17px;
    color: #555;
    margin-bottom: 8px;
  }

  .row label {
    display: block;
    margin-bottom: 6px;
    font-size: 19px;
    font-weight: 500;
    color: #082755;
  }

  h5 {
    margin-bottom: 15px;
    font-size: 22px;
    font-weight: 800;
    color: #082755;
  }

  

</style>


  <?php
  if (session_status() === PHP_SESSION_NONE) {
    session_start();
  }

  $esIndex = false;
  include 'includes/header.php';
  ?>

  <div class="row">
    <div class="col-md-3">
      <h5 style="font-size: 35px; font-weight: 1000;">Filtros</h5>

      <div class="mb-3">
        <label class="form-label"><strong>Género</strong></label>
        <div class="filter-text"><input type="checkbox" class="filter-genero" value="Acción"> Acción</div>
        <div class="filter-text"><input type="checkbox" class="filter-genero" value="Thriller"> Thriller</div>
        <div class="filter-text"><input type="checkbox" class="filter-genero" value="Terror"> Terror</div>
        <div class="filter-text"><input type="checkbox" class="filter-genero" value="Animada"> Animada</div>
        <div class="filter-text"><input type="checkbox" class="filter-genero" value="Comedia"> Comedia</div>
      </div>

      <div class="mb-3">
        <label class="form-label"><strong>Clasificación</strong></label>
        <div class="filter-text"><input type="checkbox" class="filter-clasificacion" value="APT"> APT</div>
        <div class="filter-text"><input type="checkbox" class="filter-clasificacion" value="PG-13"> PG-13</div>
        <div class="filter-text"><input type="checkbox" class="filter-clasificacion" value="+14"> +14</div>
      </div>

      <div class="mb-3">
        <label class="form-label"><strong>Disponibilidad</strong></label>
        <div class="filter-text"><input type="checkbox" class="filter-disponibilidad" value="Cartelera"> En Cartelera</div>
        <div class="filter-text"><input type="checkbox" class="filter-disponibilidad" value="Proximamente"> Próximamente</div>
      </div>

      <div class="mb-3">
        <label class="form-label"><strong>Idioma</strong></label>
        <div class="filter-text"><input type="checkbox" class="filter-idioma" value="Subtitulada"> Subtitulada</div>
        <div class="filter-text"><input type="checkbox" class="filter-idioma" value="Doblada"> Doblada</div>
      </div>

    </div>

    <div class="col-md-9" id="peliculas-container">
      <img src="https://uvk.pe/images/movie/1746213779.jpg" alt="">
    </div>
  </div>

  <script>
    const peliculas = [{
        id: 1,
        titulo: "ARMAGEDÓN: DIOSES DEL APOCALIPSIS",
        genero: "Acción",
        duracion: "2 hrs 10 min",
        clasificacion: "+14",
        estreno: "8 de mayo de 2025",
        sinopsis: "Una epopeya de fantasía oriental que recrea las prolongadas guerras míticas entre humanos, inmortales y monstruos que tuvieron lugar hace más de tres mil años.",
        disponibilidad: "Cartelera",
        idioma: ["Subtitulada", "Doblada"],
        poster: "media/portadas/armagedon.jpg"
      },
      {
        id: 2,
        titulo: "Exterminio: Evolución",
        genero: "Terror",
        duracion: "1 hr 55 min",
        clasificacion: "+16",
        estreno: "27 de junio de 2025",
        sinopsis: "Tercera entrega de la saga 'Exterminio'. Años después del brote inicial, los sobrevivientes enfrentan una nueva variante más mortal. En medio del caos, un grupo lucha por detener la evolución del virus antes de que sea irreversible.",
        disponibilidad: "Cartelera",
        idioma: ["Subtitulada", "Español"],
        poster: "media/portadas/exterminio.jpg"
      },
      {
        id: 3,
        titulo: "LILO Y STITCH",
        genero: "Animada",
        duracion: "1 hrs 40 min",
        clasificacion: "APT",
        estreno: "22 de Mayo de 2025",
        sinopsis: "Remake de acción real del clásico animado de Disney 'Lilo y Stitch'.",
        disponibilidad: "Cartelera",
        idioma: ["Doblada"],
        poster: "media/portadas/liloystich.jpg"
      },
      {
        id: 4,
        titulo: "MISION: IMPOSIBLE – LA SENTENCIA FINAL",
        genero: "Acción",
        duracion: "2 hrs 0 min",
        clasificacion: "PG-13",
        estreno: "22 de Mayo de 2025",
        sinopsis: "Nuestras vidas son la suma de nuestras decisiones. Tom Cruise es Ethan Hunt en Misión: Imposible – La Sentencia Final.",
        disponibilidad: "Cartelera",
        idioma: ["Subtitulada", "Doblada"],
        poster: "media/portadas/mision_imposible.jpg"
      },
      {
        id: 5,
        titulo: "DESTINO FINAL LAZOS DE SANGRE",
        genero: "Terror",
        duracion: "1 hrs 49 min",
        clasificacion: "PG-13",
        estreno: "15 de Mayo de 2025",
        sinopsis: "Si interfieres en los planes de la Muerte, las cosas pueden volverse muy... desordenadas.",
        disponibilidad: "Cartelera",
        idioma: ["Subtitulada"],
        poster: "media/portadas/destino_final_6.png"
      },
      {
        id: 6,
        titulo: "LA LEYENDA DE OCHI",
        genero: "Animada",
        duracion: "1 hrs 24 min",
        clasificacion: "APT",
        estreno: "15 de Mayo de 2025",
        sinopsis: "Una joven llamada Yuri crece temiendo a las criaturas solitarias del bosque conocidas como los ochi...",
        disponibilidad: "Cartelera",
        idioma: ["Doblada"],
        poster: "media/portadas/ochi.png"
      },
      {
        id: 7,
        titulo: "KARATE KID LEYENDAS",
        genero: "Acción",
        duracion: "1 hrs 40 min",
        clasificacion: "PG-13",
        estreno: "08 de Mayo de 2025",
        sinopsis: "Nueva película de la saga 'Karate Kid', conectada al universo de 'Cobra Kai'.",
        disponibilidad: "Cartelera",
        idioma: ["Subtitulada", "Doblada"],
        poster: "media/portadas/karate_kid.jpg"
      },
      {
        id: 8,
        titulo: "THUNDERBOLTS",
        genero: "Acción",
        duracion: "1 hrs 40 min",
        clasificacion: "PG-13",
        estreno: "30 de Abril de 2025",
        sinopsis: "Un mundo sin Vengadores no significa que no haya un grupo de superhéroes. Hay un grupo y se llaman Thunderbolts.",
        disponibilidad: "Cartelera",
        idioma: ["Subtitulada"],
        poster: "media/portadas/thunderbolts.jpg"
      },
      {
        id: 9,
        titulo: "Cómo Entrenar a tu Dragón",
        genero: "Acción",
        duracion: "1 hr 38 min",
        clasificacion: "APT",
        estreno: "Reestreno - 2025",
        sinopsis: "Hipo es un joven vikingo que no encaja con la tradición de su tribu: cazar dragones. Todo cambia cuando entabla amistad con un Furia Nocturna herido, a quien llama Chimuelo, desatando una historia de valentía y unión inesperada.",
        disponibilidad: "Cartelera",
        idioma: ["Español", "Subtitulada"],
        poster: "media/portadas/entrenar_a_tu_dragon.jpg"
      },
      {
        id: 10,
        titulo: "Elio",
        genero: "Animación",
        duracion: "1 hr 35 min",
        clasificacion: "+7",
        estreno: "7 de marzo de 2025",
        sinopsis: "Elio, un niño solitario con mucha imaginación, es accidentalmente transportado al Comuniverso, una organización interplanetaria que lo confunde con el embajador de la Tierra. Deberá aprender a ser un verdadero líder para salvar su planeta.",
        disponibilidad: "Cartelera",
        idioma: ["Español", "Subtitulada"],
        poster: "media/portadas/elio_pelicula.webp"
      },
      {
        id: 11,
        titulo: "SUPERMAN: LEGACY",
        genero: "Acción",
        duracion: "2 hrs 25 min",
        clasificacion: "APT",
        estreno: "11 de julio de 2025",
        sinopsis: "Clark Kent mientras trata de equilibrar su herencia kryptoniana con su educación humana. Él es la encarnación de la verdad, la justicia y el estilo estadounidense. Él es bondad, pero la realidad es que el mundo piensa que la bondad es anticuada. ¿Es entonces que puede dejar salir su lado menos humano? Superman: Legacy no es una historia de origen del Hombre de Acero, pero marcará el comienzo de la DCU.",
        disponibilidad: "Proximamente",
        idioma: ["Subtitulada", "Doblada"],
        poster: "media/portadas/superman.webp"
      }
    ];

    function mostrarPeliculas(filtroGenero = [], filtroClasificacion = [], filtroDisponibilidad = [], filtroIdioma = []) {
      const cont = document.getElementById('peliculas-container');
      cont.innerHTML = '';

      let filtradas = peliculas.filter(p => {
        const generoOK = filtroGenero.length === 0 || filtroGenero.includes(p.genero);
        const clasificacionOK = filtroClasificacion.length === 0 || filtroClasificacion.includes(p.clasificacion);
        const disponibilidadOK = filtroDisponibilidad.length === 0 || filtroDisponibilidad.includes(p.disponibilidad);
        const idiomaOK = filtroIdioma.length === 0 || filtroIdioma.some(id => p.idioma.includes(id));
        return generoOK && clasificacionOK && disponibilidadOK && idiomaOK;
      });

      filtradas.forEach(p => {
        cont.innerHTML += `
      <div class="card mb-4 mx-auto" style="max-width: 900px;">
        <div class="row g-0">
          <div class="col-md-4">
            <img src="${p.poster}" class="img-fluid rounded-start" alt="${p.titulo}">
          </div>
          <div class="col-md-8">
            <div class="card-body">
              <h5 class="card-title">${p.titulo}</h5>
<p class="card-text">${p.genero} | ${p.duracion} | ${p.clasificacion}</p>
<p class="card-text"><strong class="text-primary">Estreno</strong><br>${p.estreno}</p>
<p class="card-text"><strong class="text-primary">Sinopsis</strong><br>${p.sinopsis}</p>
<button class="btn btn-primary" onclick="window.location.href='seleccionar_butacas.php?id=${p.id}&titulo=${encodeURIComponent(p.titulo)}&poster=${encodeURIComponent(p.poster)}'">Comprar</button>

            </div>
          </div>
        </div>
      </div>
    `;
      });
    }

    function actualizarFiltros() {
      const genero = Array.from(document.querySelectorAll('.filter-genero:checked')).map(c => c.value);
      const clasificacion = Array.from(document.querySelectorAll('.filter-clasificacion:checked')).map(c => c.value);
      const disponibilidad = Array.from(document.querySelectorAll('.filter-disponibilidad:checked')).map(c => c.value);
      const idioma = Array.from(document.querySelectorAll('.filter-idioma:checked')).map(c => c.value);
      mostrarPeliculas(genero, clasificacion, disponibilidad, idioma);
    }

    document.querySelectorAll('.filter-genero, .filter-clasificacion, .filter-disponibilidad, .filter-idioma')
      .forEach(chk => chk.addEventListener('change', actualizarFiltros));

    mostrarPeliculas();
  </script>