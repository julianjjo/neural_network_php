<?php
function getTable($grid, $acciones)
{
    $table = [];
    $tamanoEstadoY = count($grid[0]);
    $tamanoEstadoX = count($grid);
    $cantidadAcciones = count($acciones);
    for ($i = 0; $i < $tamanoEstadoX; $i++) {
        for ($j = 0; $j < $tamanoEstadoY; $j++) {
            for ($accion = 0; $accion < $cantidadAcciones; $accion++) {
                $table[$i][$j][$accion] = 0;
            }
        }
    }
    return $table;
}

function getGridNumeric($grid = []){
    $numericGrid = array();
    foreach ($grid as $x => $valuex) {
        foreach ($valuex as $y => $valuey) {
            if ($grid[$x][$y] == "v") {
                $numericGrid[$x][$y] = 0;
            } elseif ($grid[$x][$y] == "j") {
                $numericGrid[$x][$y] = 1;
            } elseif ($grid[$x][$y] == "T") {
                $numericGrid[$x][$y] = 2;
            }  elseif ($grid[$x][$y] == "f") {
                $numericGrid[$x][$y] = -1;
            }
        }
    }
    return $numericGrid;
}

function gridNumericToVector($numericGrid = []){
    $vectorGrid = array();
    foreach ($numericGrid as $x => $valuex) {
        foreach ($valuex as $y => $valuey) {
            $vectorGrid[] = $valuey;
        }
    }
    return $vectorGrid;
}

function getGridLetters($numericGrid = []){
    $grid = array();
    foreach ($numericGrid as $x => $valuex) {
        foreach ($valuex as $y => $valuey) {
            if ($numericGrid[$x][$y] == 0) {
                $grid[$x][$y] = "v";
            } elseif ($numericGrid[$x][$y] == 1) {
                $grid[$x][$y] = "j";
            } elseif ($numericGrid[$x][$y] == 2) {
                $grid[$x][$y] = "T";
            }  elseif ($numericGrid[$x][$y] == -1) {
                $grid[$x][$y] = "f";
            }
        }
    }
    return $grid;
}

function getRecompensa($grid = [], $estado = [], $accion = 0)
{
    $reward = -1;
    if (!empty($grid[$estado["y"]][$estado["x"] - 1])) {
        if ($accion == 0 && $grid[$estado["y"]][$estado["x"] - 1] == "T") {
            $reward = 1;
        } elseif ($accion == 0 && $grid[$estado["y"]][$estado["x"] - 1] == "v") {
            $reward = -0.001;
        } elseif ($accion == 0 && $grid[$estado["y"]][$estado["x"] - 1] == "f") {
            $reward = -1;
        }
    }
    if (!empty($grid[$estado["y"]][$estado["x"] + 1])) {
        if ($accion == 1 && $grid[$estado["y"]][$estado["x"] + 1] == "T") {
            $reward = 1;
        } elseif ($accion == 1 && $grid[$estado["y"]][$estado["x"] + 1] == "v") {
            $reward = -0.001;
        } elseif ($accion == 1 && $grid[$estado["y"]][$estado["x"] + 1] == "f") {
            $reward = -1;
        }
    }
    if (!empty($grid[$estado["y"] - 1][$estado["x"]])) {
        if ($accion == 2 && $grid[$estado["y"] - 1][$estado["x"]] == "T") {
            $reward = 1;
        } elseif ($accion == 2 && $grid[$estado["y"] - 1][$estado["x"]] == "v") {
            $reward = -0.001;
        } elseif ($accion == 2 && $grid[$estado["y"] - 1][$estado["x"]] == "f") {
            $reward = -1;
        }
    }

    if (!empty($grid[$estado["y"] + 1][$estado["x"]])) {
        if ($accion == 3 && $grid[$estado["y"] + 1][$estado["x"]] == "T") {
            $reward = 1;
        } elseif ($accion == 3 && $grid[$estado["y"] + 1][$estado["x"]] == "v") {
            $reward = -0.001;
        } elseif ($accion == 3 && $grid[$estado["y"] + 1][$estado["x"]] == "f") {
            $reward = -1;
        }
    }
    return $reward;
}

function nuevaPosicion($grid = [], $estado = [], $accion = 0)
{
    if ($accion == 0) {
        if (!empty($grid[$estado["y"]][$estado["x"] - 1])) {
            $grid[$estado["y"]][$estado["x"] - 1] = "j";
            $grid[$estado["y"]][$estado["x"]] = "v";
        }
    } elseif ($accion == 1) {
        if (!empty($grid[$estado["y"]][$estado["x"] + 1])) {
            $grid[$estado["y"]][$estado["x"] + 1] = "j";
            $grid[$estado["y"]][$estado["x"]] = "v";
        }
    } elseif ($accion == 2) {
        if (!empty($grid[$estado["y"] - 1][$estado["x"]])) {
            $grid[$estado["y"] - 1][$estado["x"]] = "j";
            $grid[$estado["y"]][$estado["x"]] = "v";
        }
    } elseif ($accion == 3) {
        if (!empty($grid[$estado["y"] + 1][$estado["x"]])) {
            $grid[$estado["y"] + 1][$estado["x"]] = "j";
            $grid[$estado["y"]][$estado["x"]] = "v";
        }
    }
    return $grid;
}

function validarTerminado($grid = [], $estado = [], $accion = 0)
{
    if ($accion == 0) {
        if (!empty($grid[$estado["y"]][$estado["x"] - 1])) {
            if ($grid[$estado["y"]][$estado["x"] - 1] == "T") {
                return true;
            }
        } else {
            return true;
        }
    } elseif ($accion == 1) {
        if (!empty($grid[$estado["y"]][$estado["x"] + 1])) {
            if ($grid[$estado["y"]][$estado["x"] + 1] == "T") {
                return true;
            }
        } else {
            return true;
        }
    } elseif ($accion == 2) {
        if (!empty($grid[$estado["y"] - 1][$estado["x"]])) {
            if ($grid[$estado["y"] - 1][$estado["x"]] == "T") {
                return true;
            }
        } else {
            return true;
        }
    } elseif ($accion == 3) {
        if (!empty($grid[$estado["y"] + 1][$estado["x"]])) {
            if ($grid[$estado["y"] + 1][$estado["x"]] == "T") {
                return true;
            }
        } else {
            return true;
        }
    }
    return false;
}

function getNuevoEstado($grid = [], $estado = [], $accion = 0)
{
    if ($accion == 0) {
        $nuevoEstado["x"] = $estado["x"] - 1;
        $nuevoEstado["x"] = $estado["x"] - 1;
        $nuevoEstado["y"] = $estado["y"];
        $nuevoEstado["y"] = $estado["y"];
    } elseif ($accion == 1) {
        $nuevoEstado["x"] = $estado["x"] + 1;
        $nuevoEstado["x"] = $estado["x"] + 1;
        $nuevoEstado["y"] = $estado["y"];
        $nuevoEstado["y"] = $estado["y"];
    } elseif ($accion == 2) {
        $nuevoEstado["y"] = $estado["y"] - 1;
        $nuevoEstado["y"] = $estado["y"] - 1;
        $nuevoEstado["x"] = $estado["x"];
        $nuevoEstado["x"] = $estado["x"];
    } elseif ($accion == 3) {
        $nuevoEstado["y"] = $estado["y"] + 1;
        $nuevoEstado["y"] = $estado["y"] + 1;
        $nuevoEstado["x"] = $estado["x"];
        $nuevoEstado["x"] = $estado["x"];
    }

    return $nuevoEstado;
}

function actuar($grid = [], $estado = [], $accion = 0)
{
    $values["done"] = validarTerminado($grid, $estado, $accion);
    $values["recompensa"] = getRecompensa($grid, $estado, $accion);
    $values["nueva_grilla"] = nuevaPosicion($grid, $estado, $accion);
    $values["nuevo_estado"] = getNuevoEstado($grid, $estado, $accion);
    return $values;
}

// function getAction($ann, $acciones, $numericVectorGrid, $factorDescuento)
// {
//     $randomFloat = rand(0, 100) / 100;
//     if ($randomFloat < $factorDescuento) {
//         $accion = rand(0, 3);
//         return $accion;
//     } else {
//         foreach ($acciones as $accion => $nombre) {
//             $input = $numericVectorGrid;
//             $input[] = $accion;
//             $output = fann_run($ann, $input);
//             $q_value = getQValue($output);
//             $table[$accion] = $q_value;
//         }
//         $accion = array_search(max($table), $table);
//         return $accion;
//     }
// }

function getAction($table = [], $estado = [], $factorDescuento)
{
    $randomFloat = rand(0, 100) / 100;
    if ($randomFloat < $factorDescuento) {
        $accion = rand(0, 3);
        return $accion;
    } else {
        if (!empty($table[$estado["y"]][$estado["x"]])) {
            $accion = array_search(max($table[$estado["y"]][$estado["x"]]), $table[$estado["y"]][$estado["x"]]);
            return $accion;
        }
    }
}

function getActionPredict($ann, $numericVectorGrid, $acciones)
{
    foreach ($acciones as $accion => $nombre) {
        $input = $numericVectorGrid;
        $input[] = $accion;
        $output = fann_run($ann, $input);
        $q_value = getQValue($output);
        $table[$accion] = $q_value;
    }
    $accion = array_search(max($table), $table);
    return $accion;
}

function getActionMax($table = [])
{
    $accion = array_search(max($table), $table);
    return $accion;
}

function getOuputs($q_value){
    $output[0] = 0;
    $output[1] = 0;
    if($q_value < 0){
        $output[1] = abs($q_value);
    } else{
        $output[0] = $q_value;
    }
    return $output;
}

function getQValue($output){
    $key = array_search(max($output), $output);
    if($key == 1){
        $q_value = $output[$key] * -1;
    } else {
        $q_value = $output[$key];
    }
    return $q_value;
}


function printGrid($grid)
{
    foreach ($grid as $fila) {
        foreach ($fila as $value) {
            echo "$value ->";
        }
        echo "\n";
    }
    echo "\n";
}

function linspace($initial, $final, $cantidad)
{
    $step = ($final - $initial) / ($cantidad - 1);
    return range($initial, $final, $step);
}
