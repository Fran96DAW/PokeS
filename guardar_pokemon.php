<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION["player"])) {
    echo json_encode(["error" => "No autorizado"]);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

$pokemonId = $input['pokemonId'];
$pokemonName = $input['pokemonName'];

try {
    $bd = new PDO('mysql:host=localhost;dbname=pokescore;charset=utf8', 'root', '');
} catch (Exception $e) {
    echo json_encode(["error" => "Error al conectar a la base de datos"]);
    exit;
}

$player = $_SESSION['player'];
$consulta = $bd->prepare("SELECT idPlayer FROM participantes WHERE nickPlayer=:player");
$consulta->execute([':player' => $player]);
$registro = $consulta->fetch();

if (!$registro) {
    echo json_encode(["error" => "Jugador no encontrado"]);
    exit;
}

$idPlayer = $registro["idPlayer"];

$insert = $bd->prepare("INSERT INTO participantes (idPlayer, pokemon, pokename) VALUES (:idPlayer, :pokemon, :pokename)");
$resultado = $insert->execute([':idPlayer' => $idPlayer, ':pokemon' => $pokemonId, ':pokename' => $pokemonName]);

if ($resultado) {
    echo json_encode(["success" => "Pokémon guardado correctamente"]);
} else {
    echo json_encode(["error" => "Error al guardar el Pokémon"]);
}
?>
