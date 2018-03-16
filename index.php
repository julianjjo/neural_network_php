<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
$opciones = ["piedra", "papel", "tijeras"];
$num_input = 3;
$num_output = 3;
$num_layers = 3;
$num_neurons_hidden = 3;
$desired_error = 0.0001;

function conversorOpcionValue($opcion)
{
    if ($opcion == "piedra") {
        $conversion = [1, 0, 0];
    } elseif ($opcion == "papel") {
        $conversion = [0, 1, 0];
    } elseif ($opcion == "tijeras") {
        $conversion = [0, 0, 1];
    }

    return $conversion;
}

function conversorValueOpcion($opcion)
{
    if ($opcion == [1, 0, 0]) {
        $conversion = "piedra";
    } elseif ($opcion == [0, 1, 0]) {
        $conversion = "papel";
    } elseif ($opcion == [0, 0, 1]) {
        $conversion = "tijeras";
    }

    return $conversion;
}

function getGanadorJuego($jugadaPlayer1, $jugadaPlayer2)
{
    if ($jugadaPlayer1 == $jugadaPlayer2) {
        return 0;
    } elseif ($jugadaPlayer1 == "piedra" && $jugadaPlayer2 == "papel") {
        return 2;
    } elseif ($jugadaPlayer1 == "piedra" && $jugadaPlayer2 == "tijeras") {
        return 1;
    } elseif ($jugadaPlayer1 == "papel" && $jugadaPlayer2 == "piedra") {
        return 1;
    } elseif ($jugadaPlayer1 == "papel" && $jugadaPlayer2 == "tijeras") {
        return 2;
    } elseif ($jugadaPlayer1 == "tijeras" && $jugadaPlayer2 == "papel") {
        return 1;
    } elseif ($jugadaPlayer1 == "tijeras" && $jugadaPlayer2 == "piedra") {
        return 2;
    }
}

function getRandonOption($opciones)
{
    $claves_aleatorias = array_rand($opciones);
    $opcion = $opciones[$claves_aleatorias];
    return $opcion;
}

function getResult($player2, $opciones)
{
    if ($player2[0] > 0.96) {
        $opcionPlayer2 = [1, 0, 0];
    } elseif ($player2[1] > 0.96) {
        $opcionPlayer2 = [0, 1, 0];
    } elseif ($player2[2] > 0.96) {
        $opcionPlayer2 = [0, 0, 1];
    } else {
        $opcionPlayer2 = conversorOpcionValue(getRandonOption($opciones));
    }

    return $opcionPlayer2;
}

$ann = fann_create_standard($num_layers, $num_input, $num_neurons_hidden, $num_output);
if ($ann) {
    $estadisticas = array("Ganados" => 0, "Perdidos" => 0);
    for ($i = 0; $i < 15000; $i++) {
        $opcionPlayer1 = getRandonOption($opciones);
        $opcionPlayer1 = conversorOpcionValue($opcionPlayer1);
        $player2 = fann_run($ann, $opcionPlayer1);
        $opcionPlayer2 = getResult($player2, $opciones);
        if (getGanadorJuego(conversorValueOpcion($opcionPlayer1), conversorValueOpcion($opcionPlayer2)) == 2) {
            $estadisticas["Ganados"]++;
            $result = fann_train($ann, $opcionPlayer1, $opcionPlayer2);
        } else {
            $estadisticas["Perdidos"]++;
        }
    }
    printf("Juegos Ganados %s Juegos Perdidos %s \n", $estadisticas["Ganados"], $estadisticas["Perdidos"]);
    fann_save ( $ann , "juego_piedra_papel_tijeras_net.nt" );
    chmod("juego_piedra_papel_tijeras_net.nt", 0777);
    fann_destroy($ann);
}
