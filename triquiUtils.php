<?php

function guardarMovimiento($board = [], $movement = [])
{
    $key = array_search(1, $movement);
    $board[$key] = $movement[$key];
    $board = array_map('intval', $board);
    return $board;
}

function movimientoAleatorio($board = [], $player = -1)
{
    while (true) {
        $keyRandReal = mt_rand(0, 8);
        if ($board[$keyRandReal] == 0) {
            $board[$keyRandReal] = $player;
            $board = array_map('intval', $board);
            return $board;
        }
    }
    return $board;
}

function movimientoIaAleatorio($board = [], $movement, $player = -1)
{
    while (true) {
        $keyRandReal = mt_rand(0, 8);
        if ($board[$keyRandReal] == 0) {
            $movement[$keyRandReal] = $player;
            return $movement;
        }
    }
    return $movement;
}

function esEmpate($board = [])
{
    $board = array_map('intval', $board);
    foreach ($board as $value) {
        if ($value == 0) {
            return false;
        }
    }
    return true;
}

function esGanador($board = [], $player = 1)
{
    $board = array_map('intval', $board);
    if (validateVertical($board, $player)) {
        return true;
    } elseif (validateHorizontal($board, $player)) {
        return true;
    } elseif (validateDiagonales($board, $player)) {
        return true;
    }
    return false;
}

function validateVertical($board = [], $player = 1)
{
    for ($i = 0; $i < 3; $i++) {
        if ($board[$i] == $player && $board[$i + 3] == $player && $board[$i + 6] == $player) {
            return true;
        }
    }
    return false;
}

function validateHorizontal($board = [], $player = 1)
{
    for ($i = 0; $i < 9; $i += 3) {
        if ($board[$i] == $player && $board[$i + 1] == $player && $board[$i + 2] == $player) {
            return true;
        }
    }
    return false;
}

function validateDiagonales($board = [], $player = 1)
{
    if ($board[0] == $player && $board[4] == $player && $board[8] == $player) {
        return true;
    }
    if ($board[2] == $player && $board[4] == $player && $board[6] == $player) {
        return true;
    }
    return false;
}

function getMovimiento($board = [], $movement = [])
{
    $board = array_map('intval', $board);
    $movementReal = [0, 0, 0, 0, 0, 0, 0, 0, 0];
    foreach ($movement as $key => $value) {
        if ($value >= 0.8) {
            if ($board[$key] == 0) {
                $movementReal[$key] = 1;
                return $movementReal;
            }
        }
    }
    return false;
}

function getMovimientoPlayerHuman($board = [], $posicion)
{
    $mapPoscion = array('0,0' => 0, '0,1' => 1, '0,2' => 2,
        '1,0' => 3, '1,1' => 4, '1,2' => 5,
        '2,0' => 6, '2,1' => 7, '2,2' => 8);

    if ($board[$mapPoscion[$posicion]] === 0) {
        $board[$mapPoscion[$posicion]] = -1;
        $board = array_map('intval', $board);
        return $board;
    }
    return false;
}

function getMovimientosVacios($board = [])
{
    return $key = array_keys($board, 0);
}

function minimax($board, $player)
{
    $aiPlayer = 1;
    $huPlayer = -1;
    $availSpots = getMovimientosVacios($board);

    if (esGanador($board, $huPlayer)) {
        $movi["score"] = -10;
        return $movi;
    } elseif (esGanador($board, $aiPlayer)) {
        $movi["score"] = 10;
        return $movi;
    } elseif (count($availSpots) == 0) {
        $movi["score"] = 0;
        return $movi;
    }

    $moves = array();

    for ($i = 0; $i < count($availSpots); ++$i) {
        //create an object for each and store the index of that spot
        $move = array();
        $move["index"] = $availSpots[$i];

        // set the empty spot to the current player
        $board[$availSpots[$i]] = $player;

        /*collect the score resulted from calling minimax
        on the opponent of the current player*/
        if ($player == $aiPlayer) {
            $result = minimax($board, $huPlayer);
            $move["score"] = $result["score"];
        } else {
            $result = minimax($board, $aiPlayer);
            $move["score"] = $result["score"];
        }

        // reset the spot to empty
        $board[$availSpots[$i]] = 0;

        // push the object to the array
        $moves[] = $move;
    }

    $bestMove = 0;
    if ($player === $aiPlayer) {
        $bestScore = -10000;
        for ($i = 0; $i < count($moves); $i++) {
            if ($moves[$i]["score"] > $bestScore) {
                $bestScore = $moves[$i]["score"];
                $bestMove = $i;
            }
        }
    } else {

        // else loop over the moves and choose the move with the lowest score
        $bestScore = 10000;
        for ($i = 0; $i < count($moves); $i++) {
            if ($moves[$i]["score"] < $bestScore) {
                $bestScore = $moves[$i]["score"];
                $bestMove = $i;
            }
        }
    }

// return the chosen move (object) from the moves array
    return $moves[$bestMove];
}
