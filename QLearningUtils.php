<?php
function getTable($grid, $acciones)
{
    $table = [];
    $tamanoEstadoY = count($grid[0]);
    $tamanoEstadoX = count($grid);
    $cantidadAcciones = count($acciones);
    for ($i = 0; $i < $tamanoEstadoY; $i++) {
        for ($j = 0; $j < $tamanoEstadoX; $j++) {
            for ($accion = 0; $accion < $cantidadAcciones; $accion++) {
                $table[$i][$j][$accion] = 0;
            }
        }
    }
    return $table;
}

function getRecompensa($grid = [], $estado = [], $accion = 0)
{
    $reward = -100;
    if (!empty($grid[$estado["y"]][$estado["x"] - 1])) {
        if ($accion == 0 && $grid[$estado["y"]][$estado["x"] - 1] == "T") {
            $reward = 100;
        } elseif ($accion == 0 && $grid[$estado["y"]][$estado["x"] - 1] == "v") {
            $reward = -1;
        }  elseif ($accion == 0 && $grid[$estado["y"]][$estado["x"] - 1] == "f") {
            $reward = -10;
        }
    }
    if (!empty($grid[$estado["y"]][$estado["x"] + 1])) {
        if ($accion == 1 && $grid[$estado["y"]][$estado["x"] + 1] == "T") {
            $reward = 100;
        } elseif ($accion == 1 && $grid[$estado["y"]][$estado["x"] + 1] == "v") {
            $reward = 1;
        }  elseif ($accion == 1 && $grid[$estado["y"]][$estado["x"] + 1] == "f") {
            $reward = -10;
        }
    }
    if (!empty($grid[$estado["y"] - 1][$estado["x"]])) {
        if ($accion == 2 && $grid[$estado["y"] - 1][$estado["x"]] == "T") {
            $reward = 100;
        } elseif ($accion == 2 && $grid[$estado["y"] - 1][$estado["x"]] == "v") {
            $reward = -1;
        }  elseif ($accion == 2 && $grid[$estado["y"] - 1][$estado["x"]]== "f") {
            $reward = -10;
        }
    }

    if (!empty($grid[$estado["y"] + 1][$estado["x"]])) {
        if ($accion == 3 && $grid[$estado["y"] + 1][$estado["x"]] == "T") {
            $reward = 100;
        } elseif ($accion == 3 && $grid[$estado["y"] + 1][$estado["x"]] == "v") {
            $reward = 1;
        }  elseif ($accion == 3 && $grid[$estado["y"] + 1][$estado["x"]] == "f") {
            $reward = -10;
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

function validarTeminado($grid = [], $estado = [], $accion = 0)
{
    if (!empty($grid[$estado["x"]][$estado["y"]])) {
        if ($grid[$estado["x"]][$estado["y"]] == "T") {
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
    $values["done"] = validarTeminado($grid, $estado, $accion);
    $values["recompensa"] = getRecompensa($grid, $estado, $accion);
    $values["nueva_grilla"] = nuevaPosicion($grid, $estado, $accion);
    $values["nuevo_estado"] = getNuevoEstado($grid, $estado, $accion);
    return $values;
}

function getAction($table = [], $estado = [], $factorDescuento)
{
    if (rand(0,1) < $factorDescuento) {
        $accion = rand(0,3);
        return $accion;
    } else {
        if (!empty($table[$estado["x"]][$estado["y"]])) {
            $accion = array_search(max($table[$estado["x"]][$estado["y"]]), $table[$estado["x"]][$estado["y"]]);
            return $accion;
        }
    }
}

function getActionPredict($table = [], $estado = [])
{
    if (isset($table[$estado["x"]][$estado["y"]])) {
        $accion = array_search(max($table[$estado["x"]][$estado["y"]]), $table[$estado["x"]][$estado["y"]]);
        return $accion;
    }
}

function maxRefuerzo($table, $grid, $nuevo_estado)
{
    if (isset($table[$nuevo_estado["x"]][$nuevo_estado["y"]])) {
        $accion = array_search(max($table[$nuevo_estado["x"]][$nuevo_estado["y"]]), $table[$nuevo_estado["x"]][$nuevo_estado["y"]]);
        return getRecompensa($grid, $nuevo_estado, $accion);
    }
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
