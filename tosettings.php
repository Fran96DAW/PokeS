<?php
session_start();
if ((!isset($_SESSION["to"]))&&(!isset($_SESSION["admin"]))) {
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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300;0,400;0,500;1,300;1,400&display=swap"
        rel="stylesheet">
    <title>PokéScore</title>
    <style>
        body {
    background-color: #ffffff; /* blanco */
    color: #000000; /* negro */   
    
    }
    .contenedor{
        padding-left: 150px;
        
    }

    .main-header {
        background-color: #ff0000; /* rojo */
    }

    /* Estilo para la tabla */
table {
    border-collapse: separate; /* Permite bordes redondeados en las celdas */
    border-spacing: 0; /* Elimina el espacio entre las celdas */
    width: 50%; /* Ajusta la tabla al ancho del contenedor */
     /* Borde negro para la tabla */
    border-radius: 5px; /* Bordes redondeados para la tabla */
    overflow: hidden; /* Asegura que los bordes redondeados se apliquen correctamente */
}

/* Estilo para las celdas de la tabla */
th, td {
    border: 1px solid black; /* Borde negro para las celdas */
    padding: 8px; /* Espaciado interno de las celdas */
    text-align: left; /* Alineación del texto a la izquierda */
    border-radius: 5px; /* Bordes redondeados para las celdas */
    background-color: ;
}

/* Estilo específico para las columnas */
th {
    background: rgb(255,0,0);
    background: linear-gradient(228deg, rgba(255,0,0,1) 70%, rgba(255,163,163,1) 94%, rgba(255,255,255,1) 100%); /* Color de fondo blanco para las columnas (cabeceras) */
}

/* Estilo para las demás celdas */
td {
    background-color: white; /* Color de fondo blanco para el resto de las celdas */
}



    </style>
</head>

<!--------------------- $Header ------------------------>
<body>
    <header class="main-header  l-section">
    <div class="l-container">
      <div class="l-section">
        <div class="center-block">

          <div class="center-content">
            <h1 onclick="location.href='./index.php'" class="titulo">PokéScore</h1>

          </div>
        </div>
      </div>
    </div>

  </header>
  <div class="contenedor">
        <h3>Gestión de Jugadores</h3>

        <?php
        echo "<a href='logout.php'>Logout</a>";
        //Conexion a bbdd
        try {
            $bd = new PDO('mysql:host=localhost;dbname=pokescore;charset=utf8', 'root', '');
        } catch (Exception $e) {
            die("Ocurrió un error, avisa al soporte técnico");
        }
        $consulta = $bd->query("SELECT * FROM participantes");

        //El usuario ha pulsado el botón Borrar
        if (isset($_POST["borrar"])) {

            if (isset($_POST["seleccionado"]) && !empty($_POST["seleccionado"])) {

                $idSeleccionado = $_POST["seleccionado"];

                //¿Cuál es el nombre del jugador que voy a borrar?
                $resultado = $bd->query("SELECT nickPlayer FROM participantes WHERE idPlayer=$idSeleccionado");

                $registro = $resultado->fetch();

                //Comprobamos que el registro exista
                if ($registro != false) {
                    $nickPlayer = $registro["nickPlayer"];
                }


                //Ejecutamos sentencia para borrar
                $filasBorradas = $bd->exec("DELETE FROM participantes WHERE idPlayer=$idSeleccionado");
                if ($filasBorradas == 1) {
                    echo "<p>El jugador $nickPlayer se ha borrado correctamente.</p>";
                }

            } else {
                echo '<p style="color: red; font-weight: bold;">Debes seleccionar un Jugador para borrar</p>';
            }
        }


        //El usuario ha pulsado el botón Anotar Resultados
        if (isset($_POST["anotar"])) {

            if (isset($_POST["seleccionado"]) && !empty($_POST["seleccionado"])) {

                $idSeleccionado = $_POST["seleccionado"];

                //¿Cuál es el nombre del jugador que voy a editar?
                $resultado = $bd->query("SELECT nickPlayer FROM participantes WHERE idPlayer=$idSeleccionado");

                $registro = $resultado->fetch();

                //Comprobamos que el registro exista
                if ($registro != false) {
                    $nickPlayer = $registro["nickPlayer"];
                    $win=$_POST["wins"];
                    $loses=$_POST["loses"];
                    if(($_POST["loses"])==null) {
                        $loses = 0;
                    }
                    if(($_POST["wins"])==null) {
                        $win = 0;
                    }
                }


                //Ejecutamos sentencia para editar
                $filasEditadas = $bd->exec("UPDATE participantes SET win=$win, loses=$loses WHERE idPlayer=$idSeleccionado");
                if ($filasEditadas == 1) {
                    ?><script>window.alert("Jugador editado exitosamente")</script><?php
                }

            } else {
                echo '<p style="color: red; font-weight: bold;">Selecciona un jugador para Anotar sus resultados</p>';
            }

        }



        echo "<table border=1>";
        echo "<tr>";
        echo "<th>Id</th>";
        echo "<th>Jugador</th>";
        echo "<th>Victorias</th>";
        echo "<th>Derrotas</th>";
        echo "<th>Seleccionar Jugador</th>";
        echo "</tr>";

        $resultado = $bd->query("SELECT * FROM participantes");

        echo "<form method='POST'>";
        while ($registro = $resultado->fetch()) {
            $id = $registro["idPlayer"];
            $player = $registro["nickPlayer"];
            $win = $registro["win"];
            $loses = $registro["loses"];

            echo "<tr>";
            echo "<td>$id</td>";
            echo "<td>$player</td>";
            echo "<td>$win</td>";
            echo "<td>$loses</td>";
            echo "<td><input type='radio' name='seleccionado' value='$id'></td>";
            echo "</tr>";
        }        
        echo "</table>";
        
        echo "<h3>Anotar resultados</h3>";
        echo "<span>Wins: </span>";
        echo "<input type='number' oninput='this.value = this.value < 0 ? 0 : this.value' name='wins'>";
        echo "<br>";
        echo "<br>";
        echo "<span>Loses: </span>";
        echo "<input type='number' oninput='this.value = this.value < 0 ? 0 : this.value' name='loses'>";
        echo "<br>";
        echo "<br>";
        echo "<input type='submit' class='btn-grad' name='borrar' value='Borrar'>";
        echo "<input type='submit' class='btn-grad' name='anotar' value='Anotar Resultados'>";
        echo "</form>";
        ?>
        <button class='btn-grad' onclick="window.location.href = 'newplayer.php'">Registrar nuevo Jugador</button>
        </div>
</body>
</html>


        