<?php
session_start();
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
  <link href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300;0,400;0,500;1,300;1,400&display=swap" rel="stylesheet">
  <title>PokéScore: Login</title>
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

    .titulo {
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

    .login-form {
      background-color: white;
      border-radius: 10px;
      padding: 20px;
      max-width: 400px;
      margin: 0 auto;
      margin-top: 50px;
      display: flex;
      flex-direction: column;
      width: 100%;
      justify-content: center;
      align-items: center;
    }

    .login-form h2 {
      color: #ff3b3b;
      text-align: center;
      margin-bottom: 20px;
    }

    .login-form p {
      margin: 10px 0;
    }

    .login-form input[type="text"],
    .login-form input[type="password"] {
      width: 100%;
      padding: 10px;
      margin-bottom: 15px;
      border: 1px solid #ddd;
      border-radius: 5px;
      box-sizing: border-box;
      font-size: 16px;
    }

    .login-form input[type="submit"] {
      width: 100%;
      padding: 10px;
      background-color: #ff3b3b;
      border: none;
      border-radius: 5px;
      color: white;
      font-size: 16px;
      cursor: pointer;
    }

    .login-form input[type="submit"]:hover {
      background-color: #ff1e1e;
    }

    .error-message {
      color: red;
      text-align: center;
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
  <div class="l-container">
    <div class="l-section">
      <div class="center-block">
        <div class="center-content">
          <form action="" method="post">
            <p>Usuario</p>
            <input type="text" name="nombre_usuario" id="">
            <p>Contraseña</p>
            <input type="password" name="password" id="">
            <br><br>
            <input type="submit" name="login" value="Iniciar sesión">
          </form>
          <?php
          //Controlar entradas
          if (isset($_POST['login'])) {

            if (isset($_POST['nombre_usuario']) && !empty($_POST['nombre_usuario'])) {
              $nombre_usuarioBien = true;
            } else {
              $nombre_usuarioBien = false;
            }
            if (isset($_POST['password']) && !empty($_POST['password'])) {
              $passwordBien = true;
            } else {
              $passwordBien = false;
            }

            if ($nombre_usuarioBien && $passwordBien) {

              //saneamos entradas
              $entradas = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
              $usuario = $entradas['nombre_usuario'];
              $pass = $entradas['password'];

              //Conexion a bbdd
              try {
                $bd = new PDO('mysql:host=localhost;dbname=pokescore;charset=utf8', 'root', '');
              } catch (Exception $e) {
                die("Ocurrió un error, avisa al soporte técnico");
              }

              //Comprueba usuario
              $comprobacion = '';

              // Verificar jugadores
              $consulta = $bd->query("SELECT * FROM participantes WHERE nickPlayer='$usuario'");
              if ($consulta->rowCount() == 1) {
                $comprobacion = 'player';
              }

              // Verificar organizadores
              if ($comprobacion == '') {
                $consulta = $bd->query("SELECT * FROM organizadores WHERE nombreTO='$usuario'");
                if ($consulta->rowCount() == 1) {
                  $comprobacion = 'to';
                }
              }

              // Verificar administradores
              if ($comprobacion == '') {
                $consulta = $bd->query("SELECT * FROM administradores WHERE nombreAdmin='$usuario'");
                if ($consulta->rowCount() == 1) {
                  $comprobacion = 'admin';
                }
              }

              if ($comprobacion == '') {
                echo "Error de usuario";
                exit;

              } else {
                $registro = $consulta->fetch();
                switch ($comprobacion) {

                  case 'player':

                    if (password_verify($pass, $registro['contraPlayer'])) {
                      $_SESSION['player'] = $registro['nickPlayer'];
                      header("Location:index.php");
                      echo $_SESSION['player'];
                    } else {
                      echo 'error de contraseña (player)';                      
                    }
                    break;

                  case 'to':


                    if (password_verify($pass, $registro['contraTO'])) {
                      $_SESSION['to'] = $registro['nombreTO'];
                      header("Location:index.php");
                      echo $_SESSION['to'];
                    } else {
                      echo 'error de contraseña (TO)';
                    }
                    break;

                  case 'admin':

                    $_SESSION['admin'] = $registro['nombreAdmin'];
                    echo $_SESSION['admin'];
                    header("Location:index.php");
                    break;

                }
              }

             
          
            }
          }
          ?>
        </div>
      </div>
    </div>
  </div>
  <script src="./js/script.js"></script>
</body>

</html>