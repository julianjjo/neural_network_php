<?php
require 'QLearningUtilsFann.php';

$acciones = ["atras", "adelante", "arriba", "abajo"];
$max_episodio_estados = 40;
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
$ann = fann_create_from_file ("QLearning.net");
echo "--------------Prueba IA--------------- \n\n\n";
$grid = $initalGrid;
$numericVectorGrid = gridNumericToVector(getGridNumeric($grid));
$estado["x"] = 0;
$estado["y"] = 0;
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
