<?php
require "triquiUtils.php";
error_reporting(E_ERROR | E_PARSE);

$score = array();
$juegosData = [];
for ($jugadas=0; $jugadas < 10000; $jugadas++) {
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
        $game = array_map('intval',$game);
        $movement = movimientoIaAleatorio($game, [0,0,0,0,0,0,0,0,0], $player = 1);
        $jugada = array('game' => $game, 'movement' => $movement);
        $juegoSave[] = $jugada;
        $game = guardarMovimiento($game, $movement);
        $game = array_map('intval',$game);
        if(esGanador($game) === true){
            $juego = false;
            $score["gano"]++;
            $juegosData = array_merge($juegosData, $juegoSave);
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

$myfile = fopen("triquiData.data", "w");
$num_train = count($juegosData);
$header = $num_train." 9 9";
fwrite($myfile, $header."\n");
foreach ($juegosData as $juego) {
  $game = str_replace(",", " ",json_encode($juego["game"]));
  $game = str_replace("[", "",$game);
  $game = str_replace("]", "",$game);
  $movement =str_replace(",", " ",json_encode($juego["movement"]));
  $movement =str_replace("[", "",$movement);
  $movement =str_replace("]", "",$movement);
  fwrite($myfile, $game."\n");
  fwrite($myfile, $movement."\n");
}
