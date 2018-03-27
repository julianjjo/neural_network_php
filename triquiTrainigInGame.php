<?php
require "triquiUtils.php";
error_reporting(E_ERROR | E_PARSE);

$score = array();
$num_layers = 4;
$ann = fann_create_standard_array ($num_layers , $layers = [9,9,9,9]);

for ($jugadas=0; $jugadas < 1000; $jugadas++) {
    $juego = true;
    $juegoSave = [];
    $board = [0,0,0,0,0,0,0,0,0];
    while($juego){
        $board = movimientoAleatorio($board, $player = -1);
        if(esGanador($board) === true){
            $juego = false;
            $score["gano"]++;
            continue;
        } elseif(esGanador($board, $player = -1) === true){
            $juego = false;
            $score["perdio"]++;
            continue;
        } elseif (esEmpate($board) === true) {
            $juego = false;
            $score["empato"]++;
            continue;
        }
        $movement = fann_run($ann, $board);
        if(getMovimiento($board, $movement) !== false){
            echo "movimiento IA \n";
            $movement = getMovimiento($movement);
        } else {
            $movementIndex = minimax($board, $player = 1);
            $movement = [0,0,0,0,0,0,0,0,0];
            $movement[$movementIndex["index"]] = 1;
        }
        $board = array_map('intval',$board);
        $jugada = array('board' => $board, 'movement' => $movement);
        $juegoSave[] = $jugada;
        $board = guardarMovimiento($board, $movement);
        $board = array_map('intval',$board);
        if(esGanador($board) === true){
            $juego = false;
            $score["gano"]++;
            foreach ($juegoSave as $scenario) {
                fann_train($ann, $scenario['board'],$scenario['movement']);
            }
            continue;
        } elseif(esGanador($board, $player = -1) === true){
            $juego = false;
            $score["perdio"]++;
            continue;
        } elseif (esEmpate($board) === true) {
            $juego = false;
            $score["empato"]++;
            foreach ($juegoSave as $scenario) {
                fann_train($ann, $scenario['game'],$scenario['movement']);
            }
            continue;
        }
    }
}

print_r($score);


fann_save($ann,"triqui.net");

fann_destroy($ann);
