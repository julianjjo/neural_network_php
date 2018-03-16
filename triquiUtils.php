<?php

function guardarMovimiento($game = [], $movement = [])
{
    $key = array_search(1, $movement);
    $game[$key] = $movement[$key];
    return $game;
}

function movimientoAleatorio($game = [], $player = -1)
{
    $keyRand = array_rand($game, 7);

    foreach ($keyRand as $key) {
        if ($game[$key] == 0) {
            $game[$key] = $player;
            return $game;
        }
    }
    return $game;
}

function movimientoIaAleatorio($game = [], $movement, $player = -1)
{
    $keyRand = array_rand($game, 7);
    foreach ($keyRand as $key) {

        if ($game[$key] == 0) {
            $movement[$key] = $player;
            return $movement;
        }
    }
    return $movement;
}

function esEmpate($game = [])
{
    $result = array_count_values($game);
    if (isset($result[0]) && $result[0] == 1) {
        return true;
    }
    return false;
}

function esGanador($game = [], $player = 1)
{
    if (validateVertical($game, $player)) {
        return true;
    } elseif (validateHorizontal($game, $player)) {
        return true;
    } elseif (validateDiagonales($game, $player)) {
        return true;
    }
    return false;
}

function validateVertical($game = [], $player = 1)
{
    for ($i = 0; $i < 3; $i++) {
        if ($game[$i] == $player && $game[$i + 3] == $player && $game[$i + 6] == $player) {
            return true;
        }
    }
    return false;
}

function validateHorizontal($game = [], $player = 1)
{
    for ($i = 0; $i < 9; $i += 3) {
        if ($game[$i] == $player && $game[$i + 1] == $player && $game[$i + 2] == $player) {
            return true;
        }
    }
    return false;
}

function validateDiagonales($game = [], $player = 1)
{
    if ($game[0] == $player && $game[4] == $player && $game[8] == $player) {
        return true;
    }
    if ($game[2] == $player && $game[4] == $player && $game[6] == $player) {
        return true;
    }
    return false;
}

function getMovimiento($movement = [])
{
    $movementReal = [0, 0, 0, 0, 0, 0, 0, 0, 0];
    foreach ($movement as $key => $value) {
        if ($value >= 0.98) {
            $movementReal[$key] = 1;
            return $movementReal;
        }
    }
    return false;
}

function getMovimientoPlayerHuman($game = [], $posicion)
{
    $mapPoscion = array('0,0' => 0, '1,0' => 1, '2,0' => 2,
                        '0,1' => 3, '1,1' => 4, '2,1' => 5,
                        '0,2' => 6, '1,2' => 7, '2,2' => 8);
                        
    if($game[$mapPoscion[$posicion]] == 0){
        $game[$mapPoscion[$posicion]] = -1;
        return $game;
    }
    return false;
}
