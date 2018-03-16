<?php
require "triquiUtils.php";
ini_set('max_execution_time', 300);
$num_input = 9;
$num_output = 9;
$num_layers = 3;
$num_neurons_hidden = 12;

$ann = fann_create_standard($num_layers, $num_input, $num_neurons_hidden, $num_output);
$score = array();

for ($jugadas=0; $jugadas < 10; $jugadas++) {
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
            foreach ($juegoSave as $value) {
                fann_train($ann, $value['game'], $value['movement']);
            }
        } elseif (esEmpate($game) === true) {
            $juego = false;
            $score["empato"]++;
            foreach ($juegoSave as $value) {
                fann_train($ann, $value['game'], $value['movement']);
            }
        } elseif (esGanador($game, $player = -1) === true) {
            $score["perdio"]++;
            $juego = false;
        }
    }
}

print_r($score);

fann_save($ann,"triqui.nt");

fann_destroy($ann);
