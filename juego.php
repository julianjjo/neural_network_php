<?php
require "triquiUtils.php";

$ann = fann_create_from_file ("triqui.net");
$game = [0,0,0,0,0,0,0,0,0];
$json = json_encode($game);
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $posicion = $_POST["posicion"];
    $game = json_decode($_POST["game"]);
    if($_POST["reset"] == "true"){
        $game = [0,0,0,0,0,0,0,0,0];
    }
    $game = getMovimientoPlayerHuman($game, $posicion);
    if($game === false){
        $game = json_decode($_POST["game"]);
        $resultado = "Movimiento Repetido";
        goto fin;
    }
    if(esGanador($game) === true){
        $resultado = "GANO IA";
        goto fin;
    } elseif (esGanador($game, $player = -1) === true) {
        $resultado = "GANO HUMANO";
        goto fin;
    } elseif (esEmpate($game) === true) {
        $resultado = "ES UN EMPATE";
        goto fin;
    }
    if(is_array($game) && $game != [0,0,0,0,0,0,0,0,0]){
        $movement = fann_run($ann, $game);
        print_r($game);
        print_r($movement);
        if(getMovimiento($movement) !== false){
            $movement = getMovimiento($movement);
        } else {
            $movement = movimientoIaAleatorio($game, [0,0,0,0,0,0,0,0,0], $player = 1);
        }
        $game = guardarMovimiento($game, $movement);
        if(esGanador($game) === true){
            $resultado = "GANO IA";
            goto fin;
        } elseif (esGanador($game, $player = -1) === true) {
            $resultado = "GANO HUMANO";
            goto fin;
        } elseif (esEmpate($game) === true) {
            $resultado = "ES UN EMPATE";
            goto fin;
        }
        $json = json_encode($game);
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
                <td <?php if ($game[0] == 1): ?>
                    id="O"
                <?php endif; ?><?php if ($game[0] == -1): ?>
                    id="X"
                <?php endif; ?>></td>
                <td <?php if ($game[1] == 1): ?>
                    id="O"
                <?php endif; ?><?php if ($game[1] == -1): ?>
                    id="X"
                <?php endif; ?>></td>
                <td <?php if ($game[2] == 1): ?>
                    id="O"
                <?php endif; ?><?php if ($game[2] == -1): ?>
                    id="X"
                <?php endif; ?>></td>
            </tr>
            <tr>
                <td <?php if ($game[3] == 1): ?>
                    id="O"
                <?php endif; ?><?php if ($game[3] == -1): ?>
                    id="X"
                <?php endif; ?>></td>
                <td <?php if ($game[4] == 1): ?>
                    id="O"
                <?php endif; ?><?php if ($game[4] == -1): ?>
                    id="X"
                <?php endif; ?>></td>
                <td <?php if ($game[5] == 1): ?>
                    id="O"
                <?php endif; ?><?php if ($game[5] == -1): ?>
                    id="X"
                <?php endif; ?>></td>
            </tr>
            <tr>
                <td <?php if ($game[6] == 1): ?>
                    id="O"
                <?php endif; ?><?php if ($game[6] == -1): ?>
                    id="X"
                <?php endif; ?>></td>
                <td <?php if ($game[7] == 1): ?>
                    id="O"
                <?php endif; ?><?php if ($game[7] == -1): ?>
                    id="X"
                <?php endif; ?>></td>
                <td <?php if ($game[8] == 1): ?>
                    id="O"
                <?php endif; ?><?php if ($game[8] == -1): ?>
                    id="X"
                <?php endif; ?>></td>
            </tr>
        </table>
    </body>
</html>

<?php fann_destroy($ann); ?>
