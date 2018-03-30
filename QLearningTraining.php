<?php
require 'QLearningUtils.php';

$acciones = ["atras", "adelante", "arriba", "abajo"];
$minLearningRate = 0.1;
$maxLearningRate = 1.0;
$factorDescuento = 0.8;
$episodios = 150000;
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
srand(250);

$learningRate = linspace($minLearningRate, $maxLearningRate, $episodios);
$table = getTable($grid, $acciones);
$maxRecompensa = -3000;

for ($episodio = 0; $episodio < $episodios; $episodio++) {
    $grid = $initalGrid;
    // system("clear");
    // printGrid($grid);
    // usleep(50000);
    $estado = getPosicionJugador($grid);
    $recompensaEpisodio = 0;
    for ($i = 0; $i < $max_episodio_estados; $i++) {
        $action = getAction($table, $estado, $factorDescuento);
        $values = actuar($grid, $estado, $action);
        if ($values["done"]) {
            $table[$estado["y"]][$estado["x"]][$action] = $values["recompensa"];
        } else {
            $table[$estado["y"]][$estado["x"]][$action] += $learningRate[$episodio] * ($values["recompensa"] + $factorDescuento * max($table[$values["nuevo_estado"]["y"]][$values["nuevo_estado"]["x"]]) - $table[$estado["y"]][$estado["x"]][$action]);
        }
        $grid = $values["nueva_grilla"];
        $estado = $values["nuevo_estado"];
        // system("clear");
        // printGrid($grid);
        // usleep(50000);
        $recompensaEpisodio += $values["recompensa"];
        if ($values["done"]) {
            break;
        }
    }
    echo "Episodio: $episodio Recompensa: $recompensaEpisodio \n";
    if ($maxRecompensa < $recompensaEpisodio) {
        $maxRecompensa = $recompensaEpisodio;
    }
}
echo "Recompensa Maxima: $maxRecompensa \n";
$fp = fopen('QLearningIA.json', 'w');
fwrite($fp, json_encode($table));
fclose($fp);
