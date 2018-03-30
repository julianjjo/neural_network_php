<?php
require 'QLearningUtils.php';

$acciones = ["atras", "adelante", "arriba", "abajo"];
$max_episodio_estados = 50;
$grid = array (
  0 =>
  array (
    0 => 'T',
    1 => 'v',
    2 => 'f',
    3 => 'v',
    4 => 'v',
  ),
  1 =>
  array (
    0 => 'f',
    1 => 'v',
    2 => 'v',
    3 => 'v',
    4 => 'v',
  ),
  2 =>
  array (
    0 => 'f',
    1 => 'f',
    2 => 'f',
    3 => 'f',
    4 => 'v',
  ),
  3 =>
  array (
    0 => 'f',
    1 => 'f',
    2 => 'f',
    3 => 'f',
    4 => 'v',
  ),
  4 =>
  array (
    0 => 'f',
    1 => 'v',
    2 => 'v',
    3 => 'j',
    4 => 'v',
  ),
  5 =>
  array (
    0 => 'v',
    1 => 'v',
    2 => 'v',
    3 => 'v',
    4 => 'f',
  ),
  6 =>
  array (
    0 => 'v',
    1 => 'v',
    2 => 'v',
    3 => 'v',
    4 => 'f',
  ),
);
$initalGrid = $grid;
$fichero = file_get_contents('./QLearningIA.json', true);
$table = json_decode($fichero);
echo "--------------Prueba IA--------------- \n\n\n";
$grid = $initalGrid;
$estado = getPosicionJugador($grid);
sleep(1);
system("clear");
printGrid($grid);
sleep(1);
for ($i = 0; $i < $max_episodio_estados; $i++) {
    $action = getActionPredict($table, $estado);
    $values = actuar($grid, $estado, $action);
    $grid = $values["nueva_grilla"];
    $estado = $values["nuevo_estado"];
    system("clear");
    printGrid($grid);
    usleep(400000);
    if ($values["done"]) {
        break;
    }
}
