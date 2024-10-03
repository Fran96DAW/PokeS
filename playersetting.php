<?php
session_start();
if ((!isset($_SESSION["player"])) && (!isset($_SESSION["admin"]))) {
    header("Location:index.php");
    exit;
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
    <link href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300;0,400;0,500;1,300;1,400&display=swap"
        rel="stylesheet">
    <title>PokéScore: Login</title>
</head>
<style>
    body {
        background-color: #ffffff;
        /* blanco */
        color: #000000;
        /* negro */

    }

    .main-header {
        background-color: #ff0000;
        /* rojo */
    }

    .l-container {
        justify-content: center;
    }

    body {
            background-color: ; /* Color de fondo azul */
            background-image: url('images/pokeball.png'); /* Ruta a tu imagen .png */
            background-repeat: repeat; /* Repetir la imagen en ambas direcciones */
            background-size: 15%; /* Tamaño de la imagen como está */
            margin: 0; /* Eliminar los márgenes por defecto del body */
            padding: 0; /* Eliminar el padding por defecto del body */
        }
</style>

<!--------------------- $Header ------------------------>
<header class="main-header  l-section">
    <div class="l-container">
        <div class="l-section">
            <div class="center-content">
                <h1 onclick="location.href='./index.php'" class="titulo">PokéScore</h1>
            </div>
        </div>
    </div>

</header>

<?php
//Conexion a bbdd
try {
    $bd = new PDO('mysql:host=localhost;dbname=pokescore;charset=utf8', 'root', '');
} catch (Exception $e) {
    die("Ocurrió un error, avisa al soporte técnico");
}
$player = $_SESSION['player'];
$consulta = $bd->query("SELECT * FROM participantes WHERE nickPlayer='$player'");
while ($registro = $consulta->fetch()) {
    $idPlayer = $registro["idPlayer"];
    $nickPlayer = $registro["nickPlayer"];
    $pokemon = $registro["pokemon"];
    $pokename = $registro["pokename"];
    $color = $registro["color"];
    $win = $registro["win"];
    $loses = $registro["loses"];
}
?>


<div class="cuadro-datos">

    <div class="datosedit">
        <h3>
            Tus datos:
        </h3>
        <p>Nombre de Entrenador: <b><?php echo $nickPlayer ?></b></p>
        <p>Victorias:<?php echo $win ?></p>
        <p>Derrotas:<?php echo $loses ?></p>
    </div>




    <div class="formulario">

        <form method="post">
            <span>Color de ficha:</span>
            <input type="color" name="color" value="<?php echo $color ?>">
            <br>
            <h3>Pokemon principal:</h3>
            <div id="pokemon">
                <div class="pokemon-img stat">
                    <h3 id="nombrebbdd"><?php echo $pokename ?></h3>
                    <img id="imgbbdd"
                        src="https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/<?php echo $pokemon ?>.png">
                </div>
            </div>
            <span id="nombre"></span>
            <br><br>

            <div class="cuadro">
                <h3>Cambiar Pokémon principal:</h3>
                <input type="Text" name="pokeid" placeholder="ID del Pokemon">
                <input type="text" name="pokenombre" placeholder="Nombre de pokemon">
                <input type="submit" class="btn-grad" value="Guardar cambios" name="registro">
            </div>

        </form>

    </div>


    <div class="buscador">
        <h3>Buscador de Pokemon</h3>
        <input type="text" id="textoInput" placeholder="Introduce nombre del Pokemon a buscar y pulsa Enter">
        <button class="btn-grad" onclick="buscaPokemon()">Buscar</button>
        <div id="listaBotones" class="listabotones"></div>
        <div id="carta" class="carta" hidden>
            <h3 id="selected"></h3>
            <h3>Nombre del pokemon:</h3>
            <h4 id="pokenombre" name="pokenombre"></h4>
            <img id="img" class="pokemon-img" hidden>
            <div id="pokemon">
                <h3>Id del Pokemon:</h3>
                <h2 id="idpokemon" name="idpokemon"></h2>
            </div>
        </div>
    </div>
    <!-- <input type="text" id="textoInput" #onkeyup="buscaPokemon()" placeholder="Introduce nombre del Pokemon a buscar y pulsa Enter">
        <button onclick="buscaPokemon()">Search</button>
        <div id="listaBotones"></div>
        <div class="carta">
            <h3 id="selected"></h3>
            <h4 id="pokenombre"></h4>
            <img id="img" class="pokemon-img" hidden>
            <div id="pokemon">
                <div id="idpokemon"></div>                    
            </div>
        </div> -->


    <!-- <div id="pokemon">
            <div class="pokemon-img stat">
                <img id="imgbbdd" src="https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/<?php echo $pokemon ?>.png">                
                <h3 id="nombrebbdd">Marshadow</h3>
            </div>
        </div> -->


    <?php
    if (isset($_POST["registro"])) {
        $color = $_POST["color"];
        $pokeid = $_POST["pokeid"];
        $pokenombre = $_POST["pokenombre"];
        $filasDevueltas = $bd->exec("UPDATE participantes SET color = '$color', pokemon='$pokeid', pokename='$pokenombre' WHERE idPlayer = $idPlayer");
        if ($filasDevueltas) {
            echo "realizado con exito";
            ?>
            <script> window.location.reload()</script><?php
        }

    }
    ?>
</div>

</.cuadro .datos>
<script type="module" src="js/app.js"></script>

</html>