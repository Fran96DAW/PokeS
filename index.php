<?php
session_start();

// Conexión a la base de datos
try {
  $bd = new PDO('mysql:host=localhost;dbname=pokescore;charset=utf8', 'root', '');
} catch (Exception $e) {
  die("Ocurrió un error, avisa al soporte técnico");
}

// Obtener información de los jugadores, ordenados por el número de victorias y luego por la diferencia de victorias menos derrotas
$query = $bd->query("SELECT nickPlayer, pokemon, win, loses, color, pokename, (win - loses) AS diff FROM participantes ORDER BY win DESC, diff DESC LIMIT 3");
$players = $query->fetchAll(PDO::FETCH_ASSOC);

// Función para determinar si un color es oscuro
function isDarkColor($color)
{
  $color = ltrim($color, '#');
  if (strlen($color) == 6) {
    list($r, $g, $b) = array_map('hexdec', str_split($color, 2));
  } else {
    return false;
  }
  $luminance = 0.2126 * $r + 0.7152 * $g + 0.0722 * $b;
  return $luminance < 128;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="theme-color" content="#f44336">
  <link rel="icon" href="./images/icons/favicon.ico">
  <link rel="stylesheet" href="./css/normalize.css">
  <link rel="stylesheet" href="./css/style.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Rowdies:wght@300;400;700&display=swap" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300;0,400;0,500;1,300;1,400&display=swap"
    rel="stylesheet">
  <title>PokéScore</title>

  <style>
    body {
            background-color: ; /* Color de fondo azul */
            background-image: url('images/pokeball.png'); /* Ruta a tu imagen .png */
            background-repeat: repeat; /* Repetir la imagen en ambas direcciones */
            background-size: 15%; /* Tamaño de la imagen como está */
            margin: 0; /* Eliminar los márgenes por defecto del body */
            padding: 0; /* Eliminar el padding por defecto del body */
        }

    .main-header {
      background: rgb(255,0,0);
      background: linear-gradient(180deg, rgba(255,0,0,1) 44%, rgba(2,0,36,1) 49%, rgba(245,246,246,1) 57%);
      padding: 20px 0;
      text-align: center;
    }

    .main-header a {
      margin: 10px;
      /* Ajusta el valor según el espacio deseado */
    }

    .titulo,
    h2 {
      
      cursor: pointer;
      font-family: 'Rowdies', cursive;
      color: white;
      font-size: 36px;
      margin: 0;
      transition: transform 0.3s ease;
    }

    .titulo:hover {
      transform: scale(1.1);
    }

    a:hover{
      transform: scale(1.1);
      transition: transform 0.3s ease;
    }

    .player-card:hover {
      transform: scale(1.1);
      transition: transform 0.3s ease;
    }

    .player-card {
      width: 500px;
      height: 300px;
      padding: 10px;
      margin: 10px;
      border-radius: 10px;
      text-align: center;      
      background-color: #fff;
      color: #f44336;
      border: 10px ;
    }

    .player-card h2 {
      font-size: 40px;
      width: 100%;
    }

    .player-card p {
      margin: 5px 0;
    }

    .player-card:nth-child(odd) {
      background-color: #f44336;
      color: #fff;
    }
  </style>
  <style>
    .player-card {
      padding: 10px;
      margin: 10px;
      border-radius: 5px;
      text-align: center;
      display: flex;
      flex-direction: row;
      flex-wrap: wrap;
      
    }

    .pokemon-img{
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    .player-card h3{
      margin-top: 5px;
    }

    .player-card img{
      width: 150px;
      height: 150px;
    
      
    }

    .win{
      color: lightgreen;
      text-shadow: -2px -2px 0 black, 2px -2px 0 black, -2px 2px 0 black, 2px 2px 0 black;
    }

    .lose{
      padding: 20px;
      color: red;
      text-shadow: -2px -2px 0 black, 2px -2px 0 black, -2px 2px 0 black, 2px 2px 0 black;
    }

    .players-section{
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    .datos{
      padding: 2px;
      border: 2px solid gold;
      background-color: ;
      border-radius: 10px;
    }

    .bienvenido{
      color: black;
    }

    .top3{
      color: black;
    }

  </style>
</head>

<!--------------------- $Header ------------------------>
<header class="main-header l-section">
  <h1 class="titulo">PokéScore</h1>
  <?php
  if (isset($_SESSION['player'])) {
    $usuario = $_SESSION['player'];
    echo "<h3 class='bienvenido'>Bienvenido/a $usuario (Entenador)</h3>";
    echo "<a href='playersetting.php'>Editar ficha Jugador</a>";
    echo "<a href='logout.php'>Logout</a>";
  }

  if (isset($_SESSION['to'])) {
    $usuario = $_SESSION['to'];
    echo "<h3 class='bienvenido'>Bienvenido/a $usuario (TO)</h3>";
    echo "<a href='tosettings.php'>Gestionar Jugadores</a>";
    echo "<a href='logout.php'>Logout</a>";
  }

  if (isset($_SESSION['admin'])) {
    $usuario = $_SESSION['admin'];
    echo "<h3 class='bienvenido'>Bienvenido/a $usuario (Admin)</h3>";
    echo "<a href='tosettings.php'>Gestionar Jugadores</a>";
    echo "<a href='toedit.php'>Editar TOs</a>";
    echo "<a href='logout.php'>Logout</a>";
  }

  if ((!isset($_SESSION['player'])) && (!isset($_SESSION['to'])) && (!isset($_SESSION['admin']))) {
    echo "<a href='login.php'>Login</a>";
  }
  ?>
</header>
<body>
  <section class="players-section">
    <h2 class="top3">Top 3 Jugadores de PokéScore</h2>
    <?php foreach ($players as $player): ?>
      <?php
      $color = htmlspecialchars($player['color']);
      $textColor = isDarkColor($color) ? '#FFFFFF' : '#000000';
      $pokemon = $player['pokemon'];
      $pokename=$player['pokename'];
      ?>
      <div class="player-card" style="background-color: <?php echo $color; ?>; color: <?php echo $textColor; ?>;">
        <h2><?php echo htmlspecialchars($player['nickPlayer']); ?></h2>
        <h4>Pokémon Principal:</h4>        
          <?php if ($player['pokemon'] != 0) { 
            echo "<div class='pokemon-img stat'>
            <h3 id='nombre'>$pokename</h3>
            <img id='img' src='https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/$pokemon.png'>
            </div>"; } else { echo '<h3>Sin Especificar</h3>'; } ?>

        <div class="datos">
          <b class="win">Victorias: <?php echo htmlspecialchars($player['win']); ?></b>
          <b class="lose">Derrotas: <?php echo htmlspecialchars($player['loses']); ?></b>
        </div>

      </div>
    <?php endforeach; ?>
  </section>
  <script src="./js/script.js"></script>
</body>

</html>