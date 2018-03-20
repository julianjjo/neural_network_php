<?php

function guardarMovimiento($game = [], $movement = [])
{
    $key = array_search(1, $movement);
    $game[$key] = $movement[$key];
    $game = array_map('intval',$game);
    return $game;
}

function movimientoAleatorio($game = [], $player = -1)
{
    while(true) {
        $keyRandReal = mt_rand(0,8);
        if ($game[$keyRandReal] == 0) {
            $game[$keyRandReal] = $player;
            $game = array_map('intval',$game);
            return $game;
        }
    }
    return $game;
}

function movimientoIaAleatorio($game = [], $movement, $player = -1)
{
    while(true) {
        $keyRandReal = mt_rand(0,8);
        if ($game[$keyRandReal] == 0) {
            $movement[$keyRandReal] = $player;
            return $movement;
        }
    }
    return $movement;
}

function esEmpate($game = [])
{
    $game = array_map('intval',$game);
    foreach ($game as $value) {
        if($value == 0){
            return false;
        }
    }
    return true;
}

function esGanador($game = [], $player = 1)
{
    $game = array_map('intval',$game);
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

function getMovimiento($game = [], $movement = [])
{
    $game = array_map('intval',$game);
    $movementReal = [0, 0, 0, 0, 0, 0, 0, 0, 0];
    foreach ($movement as $key => $value) {
        if ($value >= 0.9) {
            if ($game[$key] == 0) {
                $movementReal[$key] = 1;
                return $movementReal;
            }
        }
    }
    return false;
}

function getMovimientoPlayerHuman($game = [], $posicion)
{
    $mapPoscion = array('0,0' => 0, '1,0' => 1, '2,0' => 2,
                        '0,1' => 3, '1,1' => 4, '2,1' => 5,
                        '0,2' => 6, '1,2' => 7, '2,2' => 8);

    if($game[$mapPoscion[$posicion]] === 0){
        $game[$mapPoscion[$posicion]] = -1;
        $game = array_map('intval',$game);
        return $game;
    }
    return false;
}
