<?php
session_start();
if ((!isset($_SESSION["to"])) && (!isset($_SESSION["admin"]))) {
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
            background-color: #ffffff;
            /* blanco */
            color: #000000;
            /* negro */
        }

        .main-header {
            background-color: #ff0000;
            /* rojo */
        }

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

<body>
    <!--------------------- $Header ------------------------>
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
        <h3>Gestión de Organizadores</h3>

        <?php
        echo "<a href='logout.php'>Logout</a>";
        // Conexión a la base de datos
        try {
            $bd = new PDO('mysql:host=localhost;dbname=pokescore;charset=utf8', 'root', '');
        } catch (Exception $e) {
            die("Ocurrió un error, avisa al soporte técnico");
        }
        $consulta = $bd->query("SELECT * FROM organizadores");

        // El usuario ha pulsado el botón Borrar
        if (isset($_POST["borrar"])) {
            if (isset($_POST["seleccionado"]) && !empty($_POST["seleccionado"])) {
                $idSeleccionado = $_POST["seleccionado"];

                // ¿Cuál es el nombre del organizador que voy a borrar?
                $resultado = $bd->query("SELECT nombreTO FROM organizadores WHERE idTO=$idSeleccionado");

                $registro = $resultado->fetch();

                // Comprobamos que el registro exista
                if ($registro != false) {
                    $nombreTO = $registro["nombreTO"];
                }

                // Ejecutamos sentencia para borrar
                $filasBorradas = $bd->exec("DELETE FROM organizadores WHERE idTO=$idSeleccionado");
                if ($filasBorradas == 1) {
                    echo "<p>El organizador $nombreTO se ha borrado correctamente.</p>";
                }

            } else {
                echo '<p style="color: red; font-weight: bold;">Debes seleccionar un Organizador para borrar</p>';
            }
        }

        // El usuario ha pulsado el botón Anotar Resultados
        if (isset($_POST["anotar"])) {
            if (isset($_POST["seleccionado"]) && !empty($_POST["seleccionado"])) {
                $idSeleccionado = $_POST["seleccionado"];

                // ¿Cuál es el nombre del organizador que voy a editar?
                $resultado = $bd->query("SELECT nombreTO FROM organizadores WHERE idTO=$idSeleccionado");

                $registro = $resultado->fetch();

                // Comprobamos que el registro exista
                if ($registro != false) {
                    $nombreTO = $registro["nombreTO"];
                    $win = $_POST["wins"];
                    $loses = $_POST["loses"];
                    if (($_POST["loses"]) == null) {
                        $loses = 0;
                    }
                    if (($_POST["wins"]) == null) {
                        $win = 0;
                    }
                }

                // Ejecutamos sentencia para editar
                $filasEditadas = $bd->exec("UPDATE organizadores SET win=$win, loses=$loses WHERE idTO=$idSeleccionado");
                if ($filasEditadas == 1) {
                    ?>
                    <script>window.alert("Organizador editado exitosamente")</script><?php
                }

            } else {
                echo '<p style="color: red; font-weight: bold;">Selecciona un organizador para Anotar sus resultados</p>';
            }
        }
        echo "<table border=1>";
        echo "<tr>";
        echo "<th>Id</th>";
        echo "<th>Organizador</th>";
        echo "<th>Seleccionar Organizador</th>";
        echo "</tr>";
        $resultado = $bd->query("SELECT * FROM organizadores");
        echo "<form method='POST'>";
        while ($registro = $resultado->fetch()) {
            $id = $registro["idTO"];
            $organizador = $registro["nombreTO"];
            echo "<tr>";
            echo "<td>$id</td>";
            echo "<td>$organizador</td>";
            echo "<td><input type='radio' name='seleccionado' value='$id'></td>";
            echo "</tr>";
        }
        echo "</table>";
        echo "<br>";
        echo "<br>";
        echo "<input type='submit' name='borrar' value='Borrar'>";
        echo "</form>";
        ?>
        <button onclick="window.location.href = 'newTO.php'">Registrar nuevo Organizador</button>
    
</body>

</html>