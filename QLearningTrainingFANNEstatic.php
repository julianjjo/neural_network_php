<?php
require 'QLearningUtilsFann.php';

$acciones = ["atras", "adelante", "arriba", "abajo"];
$minLearningRate = 0.1;
$maxLearningRate = 1.0;
$factorDescuento = 0.7;
$ephilon = 0.5;
$episodios = 300000;
$max_episodio_estados = 62;
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
$num_layers = 3;
$ann = fann_create_standard_array($num_layers, $layers = [64, 64, 2]);
srand(250);
$learningRate = linspace($minLearningRate, $maxLearningRate, $episodios);
$table = getTable($grid);
$maxRecompensa = -3000;

for ($episodio = 0; $episodio < $episodios; $episodio++) {
    $grid = $initalGrid;
    $estado["x"] = 0;
    $estado["y"] = 0;
    $recompensaEpisodio = 0;
    for ($i = 0; $i < $max_episodio_estados; $i++) {
        $action = getAction($table, $estado, $factorDescuento);
        $values = actuar($grid, $estado, $action);
        $input = gridNumericToVector(getGridNumeric($grid));
        $input[] = $action;
        if ($values["done"]) {
            $table[$estado["y"]][$estado["x"]][$action] = $values["recompensa"];
        } else {
            $table[$estado["y"]][$estado["x"]][$action] += $learningRate[$episodio] * ($values["recompensa"] + $factorDescuento * max($table[$values["nuevo_estado"]["y"]][$values["nuevo_estado"]["x"]]) - $table[$estado["y"]][$estado["x"]][$action]);
        }
        $grid = $values["nueva_grilla"];
        $estado = $values["nuevo_estado"];
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
system("clear");
var_dump($table);
sleep(1);
echo "Entrenamiento Red Neuronal";
$episodios = 1000000;

for ($episodio = 0; $episodio < $episodios; $episodio++) {
    $grid = $initalGrid;
    $estado["x"] = 0;
    $estado["y"] = 0;
    $recompensaEpisodio = 0;
    for ($i = 0; $i < $max_episodio_estados; $i++) {
        $action = getAction($table, $estado, $factorDescuento);
        $values = actuar($grid, $estado, $action);
        $input = gridNumericToVector(getGridNumeric($grid));
        $input[] = $action;
        $outputs = getOuputs($table[$estado["y"]][$estado["x"]][$action]);
        fann_train($ann, $input, $outputs);
        $grid = $values["nueva_grilla"];
        $estado = $values["nuevo_estado"];
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

fann_save($ann, "QLearning.net");

fann_destroy($ann);
