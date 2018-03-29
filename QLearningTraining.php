<?php
require 'QLearningUtils.php';

$acciones = ["atras", "adelante", "arriba", "abajo"];
$minLearningRate = 0.1;
$maxLearningRate = 1.0;
$factorDescuento = 0.8;
$episodios = 300000;
$max_episodio_estados = 50;
$grid[] = ["j", "v", "f", "v", "v", "f", "v"];
$grid[] = ["v", "v", "v", "f", "v", "v", "v"];
$grid[] = ["v", "v", "v", "v", "v", "f", "v"];
$grid[] = ["v", "v", "f", "v", "v", "f", "v"];
$grid[] = ["v", "v", "v", "v", "v", "f", "v"];
$grid[] = ["v", "f", "v", "v", "v", "v", "v"];
$grid[] = ["v", "v", "f", "f", "v", "v", "v"];
$grid[] = ["v", "f", "T", "v", "v", "v", "v"];
$grid[] = ["f", "v", "v", "v", "v", "f", "f"];
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
    $estado["x"] = 0;
    $estado["y"] = 0;
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
