<?php
require 'QLearningUtils.php';

$acciones = ["atras", "adelante", "arriba", "abajo"];
$max_episodio_estados = 40;
$grid[] = ["j", "f", "v", "v", "v"];
$grid[] = ["v", "f", "v", "f", "v"];
$grid[] = ["v", "v", "v", "v", "v"];
$grid[] = ["f", "f", "v", "f", "v"];
$grid[] = ["v", "v", "v", "v", "v"];
$grid[] = ["v", "v", "f", "v", "v"];
$grid[] = ["v", "v", "v", "T", "v"];
$initalGrid = $grid;
$fichero = file_get_contents('./QLearningIA.json', true);
$table = json_decode($fichero);
echo "--------------Prueba IA--------------- \n\n\n";
$grid = $initalGrid;
$estado["x"] = 0;
$estado["y"] = 0;
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
    usleep(100000);
    if ($values["done"]) {
        break;
    }
}
