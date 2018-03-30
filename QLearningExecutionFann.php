<?php
require 'QLearningUtilsFann.php';

$acciones = ["atras", "adelante", "arriba", "abajo"];
$max_episodio_estados = 40;
$grid[] = ["v", "v", "v", "v", "v"];
$grid[] = ["v", "v", "v", "v", "v"];
$grid[] = ["v", "v", "v", "v", "v"];
$grid[] = ["v", "v", "v", "v", "v"];
$grid[] = ["v", "v", "v", "v", "v"];
$grid[] = ["v", "v", "v", "v", "v"];
$grid[] = ["v", "v", "v", "v", "v"];
$grid = generarLaberintoAleatorio($grid);
$initalGrid = $grid;
printGrid($grid);
$ann = fann_create_from_file ("QLearning.net");
echo "--------------Prueba IA--------------- \n\n\n";
$grid = $initalGrid;
$numericVectorGrid = gridNumericToVector(getGridNumeric($grid));
$estado = getPosicionJugador($grid);
system("clear");
printGrid($grid);
usleep(200000);
for ($i = 0; $i < $max_episodio_estados; $i++) {
    $action = getActionPredict($ann, $numericVectorGrid, $acciones);
    $values = actuar($grid, $estado, $action);
    $grid = $values["nueva_grilla"];
    $estado = $values["nuevo_estado"];
    $numericVectorGrid = gridNumericToVector(getGridNumeric($grid));
    system("clear");
    printGrid($grid);
    usleep(200000);
    if ($values["done"]) {
        break;
    }
}

fann_destroy($ann);
