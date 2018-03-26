<?php
require 'QLearningUtils.php';

$acciones = ["atras", "adelante", "arriba", "abajo"];
$minLearningRate = 0.1;
$maxLearningRate = 1.0;
$factorDescuento = 1.0;
$episodios = 100;
$max_episodio_estados = 20;
$grid[] = ["j", "f", "v", "v", "v"];
$grid[] = ["v", "f", "v", "f", "v"];
$grid[] = ["v", "v", "v", "v", "v"];
$grid[] = ["f", "f", "v", "f", "v"];
$grid[] = ["v", "v", "v", "v", "v"];
$grid[] = ["v", "v", "f", "v", "v"];
$grid[] = ["v", "v", "v", "T", "v"];
$initalGrid = $grid;
srand(250);

$learningRate = linspace($minLearningRate, $maxLearningRate, $episodios);
$table = getTable($grid, $acciones);
$maxRecompensa = -3000;

for ($episodio = 0; $episodio < $episodios; $episodio++) {
    $grid = $initalGrid;
    // printGrid($grid);
    // usleep(50000);
    // system("clear");
    $estado["x"] = 0;
    $estado["y"] = 0;
    $recompensaEpisodio = 0;
    for ($i = 0; $i < $max_episodio_estados; $i++) {
        $action = getAction($table, $estado, $factorDescuento);
        $values = actuar($grid, $estado, $action);
        $siguienteRecompenzaMixta = maxRefuerzo($table, $grid, $values["nuevo_estado"]);
        if ($values["done"]) {
            $table[$estado["y"]][$estado["x"]][$action] = $values["recompensa"];
        } else {
            $table[$estado["y"]][$estado["x"]][$action] += $learningRate[$episodio] * ($values["recompensa"] + $factorDescuento * $siguienteRecompenzaMixta - $table[$estado["y"]][$estado["x"]][$action]);
        }
        $grid = $values["nueva_grilla"];
        $estado = $values["nuevo_estado"];
        $recompensaEpisodio += $values["recompensa"];
        // printGrid($grid);
        // usleep(50000);
        // system("clear");
        if ($values["done"]) {
            break;
        }
    }
    echo "Episodio: $episodio Recompensa: $recompensaEpisodio \n";
    // usleep(50000);
    if ($maxRecompensa < $recompensaEpisodio) {
        $maxRecompensa = $recompensaEpisodio;
    }
}
echo "Factor de descuento final: $factorDescuento \n";
echo "Recompensa Maxima: $maxRecompensa \n";
$fp = fopen('QLearningIA.json', 'w');
fwrite($fp, json_encode($table));
fclose($fp);
