<?php
require "triquiUtils.php";
ini_set('max_execution_time', 300);
error_reporting(E_ERROR | E_PARSE);

$num_layers = 6;

$ann = fann_create_standard_array ( $num_layers , $layers = [9,9,9,9,9,9]);
$score = array();

for ($jugadas=0; $jugadas < 120000; $jugadas++) {
    $juego = true;
    $juegoSave = [];
    $game = [0,0,0,0,0,0,0,0,0];
    while($juego){
        $game = movimientoAleatorio($game);
        $movement = fann_run($ann, $game);
        if(getMovimiento($movement) !== false){
            $score["movimientoIA"]++;
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
