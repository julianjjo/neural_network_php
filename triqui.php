<?php
require "triquiUtils.php";

$num_input = 9;
$num_output = 9;
$num_layers = 4;
$num_neurons_hidden = 9;

$ann = fann_create_standard($num_layers, $num_input, $num_neurons_hidden, $num_output);
$score = array();

for ($jugadas=0; $jugadas < 10000; $jugadas++) {
    $juego = true;
    $juegoSave = [];
    $game = [0,0,0,0,0,0,0,0,0];
    while($juego){
        $game = movimientoAleatorio($game);
        $movement = fann_run($ann, $game);
        if(getMovimiento($movement) !== false){
            $movement = getMovimiento($movement);
        } else {
            $movement = movimientoIaAleatorio($game, [0,0,0,0,0,0,0,0,0], $player = 1);
        }
        $jugada = array('game' => $game, 'movement' => $movement);
        $juegoSave[] = $jugada;
        $game = guardarMovimiento($game, $movement);
        if(esGanador($game) === true){
            $juego = false;
            $score["gano"]++;
            foreach ($juegoSave as $jugada) {
                fann_train($ann, $jugada['game'], $jugada['movement']);
            }
        } elseif (esEmpate($game) === true) {
            $juego = false;
            $score["empato"]++;
            foreach ($juegoSave as $jugada) {
                fann_train($ann, $jugada['game'], $jugada['movement']);
            }
        } elseif (esGanador($game, $player = -1) === true) {
            $score["perdio"]++;
            $juego = false;
        }
    }
}

fann_save($ann,"triqui.nt");

fann_destroy($ann);
