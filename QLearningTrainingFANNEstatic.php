<?php
require 'QLearningUtilsFann.php';

$acciones = ["atras", "adelante", "arriba", "abajo"];
$minLearningRate = 0.1;
$maxLearningRate = 1.0;
$factorDescuento = 0.7;
$ephilon = 0.5;
$episodiosQLearning = 150000;
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
$num_layers = 3;
$ann = fann_create_standard_array($num_layers, $layers = [36, 36, 2]);
srand(250);
$learningRate = linspace($minLearningRate, $maxLearningRate, $episodiosQLearning);
$table = getTable($grid, $acciones);
$maxRecompensa = -3000;

for ($episodio = 0; $episodio < $episodiosQLearning; $episodio++) {
    $grid = $initalGrid;
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
        $grid = getNewGrid($initalGrid, $estado,  $values["nuevo_estado"]["x"], $values["nuevo_estado"]["y"]);
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
$trainingData = [];
$countTrainingData = 0;
foreach ($initalGrid as $estadoY => $valueY) {
    foreach ($valueY as $estadoX => $valueX) {
        $grid = $initalGrid;
        $estado = getPosicionJugador($grid);
        $grid = getNewGrid($grid, $estado, $estadoX, $estadoY);
        $estado["x"] = $estadoX;
        $estado["y"] = $estadoY;
        foreach ($acciones as $action => $value) {
            $input = gridNumericToVector(getGridNumeric($grid));
            $input[] = $action;
            $outputs = getOuputs($table[$estado["y"]][$estado["x"]][$action]);
            $trainingData[$countTrainingData]["input"] = $input;
            $trainingData[$countTrainingData]["output"] = $outputs;
            $countTrainingData += 1;
        }
    }
}
$episodio = 0;
while(true){
    $estado["x"] = 0;
    $estado["y"] = 0;
    foreach ($trainingData as $data) {
        fann_train ($ann, $data["input"], $data["output"]);
        fann_test ($ann, $data["input"], $data["output"]);
        $error = fann_get_MSE ($ann);
        echo "Error: $error \n";
        if($error <= 0.01){
            break 2;
        }
    }
    echo "Episodio: $episodio \n";
    $episodio++;
}

fann_save($ann, "QLearningEstatic.net");

fann_destroy($ann);
