<?php 
session_start();
//si se encuentra loggeado, se cierra sesion, borra cookies y redirige a login.php
if(isset ($_SESSION)){

    session_destroy();    
    header('Location: index.php');
    
}

?>