<?php
require "triquiUtils.php";

$board = [0,0,0,0,0,0,0,0,0];
$json = json_encode($board);
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $posicion = $_POST["posicion"];
    $board = json_decode($_POST["game"]);
    if($_POST["reset"] == "true"){
        $board = [0,0,0,0,0,0,0,0,0];
    }
    $board = getMovimientoPlayerHuman($board, $posicion);
    if($board === false){
        $board = json_decode($_POST["game"]);
        $resultado = "Movimiento Repetido";
        goto fin;
    }
    if(esGanador($board) === true){
        $resultado = "GANO IA";
        goto fin;
    } elseif (esGanador($board, $player = -1) === true) {
        $resultado = "GANO HUMANO";
        goto fin;
    } elseif (esEmpate($board) === true) {
        $resultado = "ES UN EMPATE";
        goto fin;
    }
    if(is_array($board) && $board != [0,0,0,0,0,0,0,0,0]){
        $movement = minimax($board, $player = 1);
        $board[$movement["index"]] = 1;
        if(esGanador($board) === true){
            $resultado = "GANO IA";
            goto fin;
        } elseif (esGanador($board, $player = -1) === true) {
            $resultado = "GANO HUMANO";
            goto fin;
        } elseif (esEmpate($board) === true) {
            $resultado = "ES UN EMPATE";
            goto fin;
        }
        $json = json_encode($board);
    }
    else{
        $noPermitido = "Esa espacio no esta vacio";
    }
}
fin:
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Juego Triqui</title>
        <style media="screen">
        table {
            width: 200px;
            border-collapse: collapse;
        }

        td {
            width: 33.333%;
            border: 6px solid #222;
        }
        td::after {
            content: '';
            display: block;
            margin-top: 100%;
        }
        #X::after {
            content: 'X';
            display: block;
            margin-top: 100%;
        }
        #O::after {
            content: 'O';
            display: block;
            margin-top: 100%;
        }
        td {
            border: 6px solid #222;
        }
        td:first-of-type {
            border-left-color: transparent;
            border-top-color: transparent;
        }
        td:nth-of-type(2) {
            border-top-color: transparent;
        }
        td:nth-of-type(3) {
            border-right-color: transparent;
            border-top-color: transparent;
        }
        tr:nth-of-type(3) td {
            border-bottom-color: transparent;
        }
        tr{
            font-size: 90px;
        }
        </style>
    </head>
    <body>
        <?php if (isset($resultado)): ?>
            <h1><?php echo $resultado ?></h1>
            <?php unset($resultado) ?>
        <?php endif; ?>
        <form class="" action="#" method="post">
            Posición: <input type="text" name="posicion" placeholder="0,1">
            <input type="hidden" name="game" value="<?php echo $json ?>">
            Reset: <select name="reset">
                <option value="false" selected>No</option>
                <option value="true">Si</option>
            </select>
            <button type="submit" name="button">Jugar</button>
        </form>
        <?php if (isset($noPermitido)): ?>
            <p><?php echo $noPermitido ?></p>
            <?php unset($noPermitido) ?>
        <?php endif; ?>
        <table id="board">
            <tr>
                <td <?php if ($board[0] == 1): ?>
                    id="O"
                <?php endif; ?><?php if ($board[0] == -1): ?>
                    id="X"
                <?php endif; ?>></td>
                <td <?php if ($board[1] == 1): ?>
                    id="O"
                <?php endif; ?><?php if ($board[1] == -1): ?>
                    id="X"
                <?php endif; ?>></td>
                <td <?php if ($board[2] == 1): ?>
                    id="O"
                <?php endif; ?><?php if ($board[2] == -1): ?>
                    id="X"
                <?php endif; ?>></td>
            </tr>
            <tr>
                <td <?php if ($board[3] == 1): ?>
                    id="O"
                <?php endif; ?><?php if ($board[3] == -1): ?>
                    id="X"
                <?php endif; ?>></td>
                <td <?php if ($board[4] == 1): ?>
                    id="O"
                <?php endif; ?><?php if ($board[4] == -1): ?>
                    id="X"
                <?php endif; ?>></td>
                <td <?php if ($board[5] == 1): ?>
                    id="O"
                <?php endif; ?><?php if ($board[5] == -1): ?>
                    id="X"
                <?php endif; ?>></td>
            </tr>
            <tr>
                <td <?php if ($board[6] == 1): ?>
                    id="O"
                <?php endif; ?><?php if ($board[6] == -1): ?>
                    id="X"
                <?php endif; ?>></td>
                <td <?php if ($board[7] == 1): ?>
                    id="O"
                <?php endif; ?><?php if ($board[7] == -1): ?>
                    id="X"
                <?php endif; ?>></td>
                <td <?php if ($board[8] == 1): ?>
                    id="O"
                <?php endif; ?><?php if ($board[8] == -1): ?>
                    id="X"
                <?php endif; ?>></td>
            </tr>
        </table>
    </body>
</html>

<?php fann_destroy($ann); ?>
