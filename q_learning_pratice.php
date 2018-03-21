<?php
$acciones = ["atras", "adelante"];
$learningRate = 0.1;
$factorDescuento = 0.5;
$episodios = 30;
$max_episodio_estados = 7;
$grid = ["j", "e", "e", "e", "e", "e", "T"];

function getTable($grid)
{
    $table = [];
    $tamanoEstado = count($grid);
    for ($i = 0; $i < $tamanoEstado; $i++) {
        for ($accion = 0; $accion < 2; $accion++) {
            $table[$i][$accion] = 0;
        }
    }
    return $table;
}

function getRecompensa($grid = [], $estado = 0, $accion = 0)
{
    $reward = 0;
    if (isset($grid[$estado])) {
        if ($accion == 1 && $grid[$estado] == "T") {
            $reward = 10;
        } elseif ($accion == 1) {
            $reward = 1;
        } else {
            $reward = -1;
        }
    }
    return $reward;
}

function nuevaPosicion($grid = [], $estado = 0, $accion = 0)
{
    if (isset($grid[$estado + 1])) {
        if ($accion == 1) {
            $grid[$estado + 1] = "j";
        }
    }
    if (isset($grid[$estado])) {
        $grid[$estado] = "e";
    }

    return $grid;
}

function validarTeminado($grid = [], $estado = 0, $accion = 0)
{

    if (!isset($grid[$estado])) {
        return true;
    } elseif ($accion == 1 && $grid[$estado] == "T") {
        return true;
    }
    return false;
}

function getNuevoEstado($grid = [], $estado = 0, $accion = 0)
{
    if ($accion == 1) {
        return $nuevoEstado = $estado + 1;
    } elseif ($accion == 0) {
        return $nuevoEstado = $estado - 1;
    }
}

function actuar($grid = [], $estado = 0, $accion = 0)
{
    $values["done"] = validarTeminado($grid, $estado, $accion);
    $values["recompensa"] = getRecompensa($grid, $estado, $accion);
    $values["nueva_grilla"] = nuevaPosicion($grid, $estado, $accion);
    $values["nuevo_estado"] = getNuevoEstado($grid, $estado, $accion);
    return $values;
}

function getAction($table = [], $estado = 0)
{
    global $factorDescuento, $acciones;
    if (rand(0, 1) < $factorDescuento) {
        $key = array_rand($acciones, 1);
        return $key;
    } else {
        if (isset($table[$estado])) {
            $accion = array_search(max($table[$estado]),$table[$estado]);
            return $accion;
        }
    }
}

function getActionPredict($table = [], $estado = 0)
{
    if (isset($table[$estado])) {
        $accion = array_search(max($table[$estado]),$table[$estado]);
        echo "$accion \n";
        return $accion;
    }
}

function maxRefuerzo($table, $grid, $nuevo_estado)
{
    global $acciones;
    if (isset($table[$nuevo_estado])) {
        $accion = array_search(max($table[$nuevo_estado]),$table[$nuevo_estado]);
        return getRecompensa($grid, $nuevo_estado, $accion);
    }
}

function printGrid($grid)
{
    foreach ($grid as $value) {
        echo "$value ->";
    }
    echo "\n";
}

$table = getTable($grid, $acciones);
for ($episodio = 0; $episodio < $episodios; $episodio++) {
    $grid = ["j", "e", "e", "e", "e", "e", "T"];
    printGrid($grid);
    sleep(1);
    system("clear");
    $estado = 0;
    $recompensaEpisodio = 0;
    for ($i = 0; $i < $max_episodio_estados; $i++) {
        $action = getAction($table, $estado);
        $values = actuar($grid, $estado, $action);
        $siguienteRecompenzaMixta = maxRefuerzo($table, $grid, $values["nuevo_estado"]);
        if (isset($table[$estado][$action])) {
            $table[$estado][$action] += $learningRate * ($values["recompensa"] + $factorDescuento * $siguienteRecompenzaMixta - $table[$estado][$action]);
        }
        $grid = $values["nueva_grilla"];
        $estado = $values["nuevo_estado"];
        $recompensaEpisodio += $values["recompensa"];
        printGrid($grid);
        sleep(1);
        system("clear");
        if ($values["done"]) {
            break;
        }
    }
    echo "Episodio: $episodio Recompensa: $recompensaEpisodio \n";
    sleep(1);
    system("clear");
}
exit;
sleep(5);
system("clear");
echo "--------------Prueba IA--------------- \n\n\n";
$grid = ["j", "e", "e", "e", "e", "e", "T"];
$estado = 0;
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
