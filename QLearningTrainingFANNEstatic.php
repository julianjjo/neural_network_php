<?php
require 'QLearningUtilsFann.php';

$acciones = ["atras", "adelante", "arriba", "abajo"];
$minLearningRate = 0.1;
$maxLearningRate = 1.0;
$factorDescuento = 0.7;
$ephilon = 0.5;
$episodiosQLearning = 150000;
$episodiosNeuralNetwork = 20000;
$max_episodio_estados = 84;

$grid[] = ["v", "v", "v", "v", "v"];
$grid[] = ["v", "v", "v", "v", "v"];
$grid[] = ["v", "v", "v", "v", "v"];
$grid[] = ["v", "v", "v", "v", "v"];
$grid[] = ["v", "v", "v", "v", "v"];
$grid[] = ["v", "v", "v", "v", "v"];
$grid[] = ["v", "v", "v", "v", "v"];
while (true) {
    $grid = generarLaberintoAleatorio($grid);
    $initalGrid = $grid;
    printGrid($grid);
    $line = readline("Continuar: ");
    if($line == "y"){
        break;
    }
}
$fp = fopen('GridGenerate.json', 'w');
fwrite($fp, json_encode($grid));
fclose($fp);
$num_layers = 4;
$ann = fann_create_standard_array($num_layers, $layers = [36, 30, 18, 2]);
srand(250);
$learningRate = linspace($minLearningRate, $maxLearningRate, $episodiosQLearning);
$table = getTable($grid);
$maxRecompensa = -3000;

for ($episodio = 0; $episodio < $episodiosQLearning; $episodio++) {
    $grid = $initalGrid;
    $estado = getPosicionJugador($grid);
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
echo "Entrenamiento Red Neuronal";
sleep(1);
for ($episodio = 0; $episodio < $episodiosNeuralNetwork; $episodio++) {
    $grid = $initalGrid;
    $estado = getPosicionJugador($grid);
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
