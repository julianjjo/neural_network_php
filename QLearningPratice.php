<?php
require 'QLearningUtils.php';

$acciones = ["atras", "adelante", "arriba", "abajo"];
$learningRate = 0.1;
$factorDescuento = 0.5;
$episodios = 20000;
$max_episodio_estados = 20;
$grid[] = ["j", "v", "v", "v", "v"];
$grid[] = ["v", "v", "f", "T", "v"];
$initalGrid = $grid;

$table = getTable($grid, $acciones);
$maxRecompensa = 0;

for ($episodio = 0; $episodio < $episodios; $episodio++) {
    $grid = $initalGrid;
    $estado["x"] = 0;
    $estado["y"] = 0;
    $recompensaEpisodio = 0;
    for ($i = 0; $i < $max_episodio_estados; $i++) {
        $action = getAction($table, $estado);
        if($action == null){
            break;
        }
        if($estado['x'] < 0 && $estado['y'] < 0 && $estado['x'] > 4 && $estado['y'] > 1){
            break;
        }
        $values = actuar($grid, $estado, $action);
        $siguienteRecompenzaMixta = maxRefuerzo($table, $grid, $values["nuevo_estado"]);
        if (isset($table[$estado['x']][$estado['y']][$action])) {
            $table[$estado['x']][$estado['y']][$action] += $learningRate * ($values["recompensa"] + $factorDescuento * $siguienteRecompenzaMixta - $table[$estado['x']][$estado['y']][$action]);
        }
        $grid = $values["nueva_grilla"];
        $estado = $values["nuevo_estado"];
        if($estado['x'] < 0 && $estado['y'] < 0){
            break;
        }
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
var_dump($table);
exit;
echo $maxRecompensa;
sleep(5);
system("clear");
echo "--------------Prueba IA--------------- \n\n\n";
$grid = $initalGrid;
$estado["x"] = 0;
$estado["y"] = 0;
sleep(1);
system("clear");
printGrid($grid);
sleep(1);
system("clear");
for ($i = 0; $i < $max_episodio_estados; $i++) {
    $action = getActionPredict($table, $estado);
    $values = actuar($grid, $estado, $action);
    $grid = $values["nueva_grilla"];
    $estado = $values["nuevo_estado"];
    printGrid($grid);
    sleep(1);
    if ($values["done"]) {
        break;
    }
    system("clear");
}
