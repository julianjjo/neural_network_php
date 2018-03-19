<?php
require "triquiUtils.php";
error_reporting(E_ERROR | E_PARSE);

$score = array();
$num_layers = 3;
$ann = fann_create_standard_array ($num_layers , $layers = [9,9,9]);

for ($jugadas=0; $jugadas < 100; $jugadas++) {
    $juego = true;
    $juegoSave = [];
    $game = [0,0,0,0,0,0,0,0,0];
    while($juego){
        $game = movimientoAleatorio($game);
        if(esGanador($game) === true){
            $juego = false;
            $score["gano"]++;
            continue;
        } elseif(esGanador($game, $player = -1) === true){
            $juego = false;
            $score["perdio"]++;
            continue;
        } elseif (esEmpate($game) === true) {
            $juego = false;
            $score["empato"]++;
            continue;
        }
        $movement = fann_run($ann, $game);
        if(getMovimiento($game, $movement) !== false){
            echo "movimiento IA \n";
            $movement = getMovimiento($movement);
        } else {
            $movement = movimientoIaAleatorio($game, [0,0,0,0,0,0,0,0,0], $player = 1);
        }
        $game = array_map('intval',$game);
        $jugada = array('game' => $game, 'movement' => $movement);
        $juegoSave[] = $jugada;
        $game = guardarMovimiento($game, $movement);
        $game = array_map('intval',$game);
        if(esGanador($game) === true){
            $juego = false;
            $score["gano"]++;
            foreach ($juegoSave as $scenario) {
                fann_train($ann, $scenario['game'],$scenario['movement']);
            }
            continue;
        } elseif(esGanador($game, $player = -1) === true){
            $juego = false;
            $score["perdio"]++;
            continue;
        } elseif (esEmpate($game) === true) {
            $juego = false;
            $score["empato"]++;
            continue;
        }
    }
}

print_r($score);


fann_save($ann,"triqui.net");

fann_destroy($ann);
