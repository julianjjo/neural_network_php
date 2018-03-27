<?php
require 'QLearningUtils.php';

$acciones = ["atras", "adelante", "arriba", "abajo"];
$learningRate = 0.1;
$factorDescuento = 0.8;
$episodios = 5000;
$max_episodio_estados = 20;
$grid[] = ["j", "v", "v", "v", "v"];
$grid[] = ["v", "f", "v", "v", "f"];
$grid[] = ["v", "v", "v", "v", "f"];
$grid[] = ["f", "f", "v", "f", "v"];
$grid[] = ["v", "v", "v", "v", "v"];
$grid[] = ["v", "v", "v", "v", "v"];
$grid[] = ["v", "v", "v", "T", "v"];
$initalGrid = $grid;
srand (250);

$table = getTable($grid, $acciones);
$maxRecompensa = 0;

for ($episodio = 0; $episodio < $episodios; $episodio++) {
    $grid = $initalGrid;
    $estado["x"] = 0;
    $estado["y"] = 0;
    $recompensaEpisodio = 0;
    for ($i = 0; $i < $max_episodio_estados; $i++) {
        $action = getAction($table, $estado, $factorDescuento);
        $values = actuar($grid, $estado, $action);
        $siguienteRecompenzaMixta = maxRefuerzo($table, $grid, $values["nuevo_estado"]);
        if ($values["done"]) {
            $table[$estado["y"]][$estado["x"]][$action] = $values["recompensa"];
        } else{
            $table[$estado["y"]][$estado["x"]][$action] += $learningRate * ($values["recompensa"] + $factorDescuento * $siguienteRecompenzaMixta - $table[$estado["y"]][$estado["x"]][$action]);
        }
        $grid = $values["nueva_grilla"];
        $estado = $values["nuevo_estado"];
        $recompensaEpisodio += $values["recompensa"];
        if ($values["done"]) {
            break;
        }
    }
    system("clear");
    echo "Episodio: $episodio Recompensa: $recompensaEpisodio \n";
    if ($maxRecompensa < $recompensaEpisodio) {
        $maxRecompensa = $recompensaEpisodio;
    }
}
$fp = fopen('QLearningIA.json', 'w');
fwrite($fp, json_encode($table));
fclose($fp);
