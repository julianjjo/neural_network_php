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

function getRecompensa($grid = [], $estado = [], $accion = 0)
{
    $reward = -100;
    if (!empty($grid[$estado["y"]][$estado["x"] - 1])) {
        if ($accion == 0 && $grid[$estado["y"]][$estado["x"] - 1] == "T") {
            $reward = 100;
        } elseif ($accion == 0 && $estado["y"] == 0 && ($estado["x"] - 1) == 0) {
            $reward = -150;
        } elseif ($accion == 0 && $grid[$estado["y"]][$estado["x"] - 1] == "v") {
            $reward = -1;
        }  elseif ($accion == 0 && $grid[$estado["y"]][$estado["x"] - 1] == "f") {
            $reward = -100;
        }
    }
    if (!empty($grid[$estado["y"]][$estado["x"] + 1])) {
        if ($accion == 1 && $grid[$estado["y"]][$estado["x"] + 1] == "T") {
            $reward = 100;
        } elseif ($accion == 1 && $grid[$estado["y"]][$estado["x"] + 1] == "v") {
            $reward = -1;
        }  elseif ($accion == 1 && $grid[$estado["y"]][$estado["x"] + 1] == "f") {
            $reward = -100;
        }
    }
    if (!empty($grid[$estado["y"] - 1][$estado["x"]])) {
        if ($accion == 2 && $grid[$estado["y"] - 1][$estado["x"]] == "T") {
            $reward = 100;
        } elseif ($accion == 0 && ($estado["y"] - 1) == 0 && $estado["x"] == 0) {
            $reward = -150;
        } elseif ($accion == 2 && $grid[$estado["y"] - 1][$estado["x"]] == "v") {
            $reward = -1;
        }  elseif ($accion == 2 && $grid[$estado["y"] - 1][$estado["x"]]== "f") {
            $reward = -100;
        }
    }

    if (!empty($grid[$estado["y"] + 1][$estado["x"]])) {
        if ($accion == 3 && $grid[$estado["y"] + 1][$estado["x"]] == "T") {
            $reward = 100;
        } elseif ($accion == 3 && $grid[$estado["y"] + 1][$estado["x"]] == "v") {
            $reward = -1;
        }  elseif ($accion == 3 && $grid[$estado["y"] + 1][$estado["x"]] == "f") {
            $reward = -100;
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
        if (!empty($table[$estado["y"]][$estado["x"]])) {
            $accion = array_search(max($table[$estado["y"]][$estado["x"]]), $table[$estado["y"]][$estado["x"]]);
            return $accion;
        }
    }
}

function getActionPredict($table = [], $estado = [])
{
    if (isset($table[$estado["y"]][$estado["x"]])) {
        $accion = array_search(max($table[$estado["y"]][$estado["x"]]), $table[$estado["y"]][$estado["x"]]);
        return $accion;
    }
}

function maxRefuerzo($table, $grid, $nuevo_estado)
{
    if (isset($table[$nuevo_estado["y"]][$nuevo_estado["x"]])) {
        $accion = array_search(max($table[$nuevo_estado["y"]][$nuevo_estado["x"]]), $table[$nuevo_estado["y"]][$nuevo_estado["x"]]);
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
