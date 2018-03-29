<?php
require 'QLearningUtils.php';

$acciones = ["atras", "adelante", "arriba", "abajo"];
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
    usleep(400000);
    if ($values["done"]) {
        break;
    }
}
