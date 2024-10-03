<?php
session_start();
if (!isset($_SESSION["admin"])) {
    header("Location:index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">

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
    <div class="contenedor rounded-div">
        <h3>Registrar Nuevo Organizador</h3>
        <form method="POST">
            <p>Nombre del Organizador</p>
            <input type="text" name="nombreTO" required>
            <p>Contraseña:</p>
            <input type="password" name="contraTO" required>
            <p>Repetir Contraseña:</p>
            <input type="password" name="pass" required>
            <input type="submit" name="registro" value="Registrar">
        </form>
        <?php
        echo "<a href='logout.php'>Logout</a>";
        // Conexión a la base de datos
        try {
            $bd = new PDO('mysql:host=localhost;dbname=pokescore;charset=utf8', 'root', '');
        } catch (Exception $e) {
            die("Ocurrió un error, avisa al soporte técnico");
        }
        if (isset($_POST["registro"])) {
            $entradas = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $nombreTO = $entradas["nombreTO"];
            $contraTO = $entradas["contraTO"];
            $pass = $entradas["pass"];
            $passEncriptada = password_hash($contraTO, PASSWORD_DEFAULT);
            if (password_verify($pass, $passEncriptada)) {
                $filasDevueltas = $bd->exec("INSERT INTO organizadores(nombreTO, contraTO) VALUES('$nombreTO', '$passEncriptada')");
                if ($filasDevueltas) {
                    echo "<script>window.alert('Organizador registrado exitosamente');</script>";
                } else {
                    echo "Error al registrar el organizador.";
                }
            } else {
                echo "Error de contraseña.";
            }
        }
        ?>
    </div>
    </header>
</body>

</html>