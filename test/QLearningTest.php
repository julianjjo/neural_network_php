<?php

require __DIR__ . '/../QLearningUtils.php';

class QLearningTest extends PHPUnit_Framework_TestCase
{
    public function testGetTable()
    {
        $acciones = ["arriba","abajo"];
        $grid[] = ["j", "v"];
        $grid[] = ["v", "T"];
        $result = getTable($grid, $acciones);
        $expect[0][0][0] = 0;
        $expect[0][0][1] = 0;
        $expect[0][1][0] = 0;
        $expect[0][1][1] = 0;
        $expect[1][0][0] = 0;
        $expect[1][0][1] = 0;
        $expect[1][1][0] = 0;
        $expect[1][1][1] = 0;
        $this->assertEquals($expect, $result, "Deberian ser el mismo array");
    }

    public function testGetRecompensaAccion0()
    {
        $estado = array('x' => 0, 'y' => 0);
        $grid[] = ["j", "v"];
        $grid[] = ["v", "T"];
        $accion = 0;
        $recompensa = getRecompensa($grid,$estado,$accion);
        $this->assertEquals(-100, $recompensa, "La recompensa deberia ser -100");
    }

    public function testGetRecompensaAccion1()
    {
        $estado = array('x' => 0, 'y' => 0);
        $grid[] = ["j", "v"];
        $grid[] = ["v", "T"];
        $accion = 1;
        $recompensa = getRecompensa($grid,$estado,$accion);
        $this->assertEquals(-1, $recompensa, "La recompensa deberia ser -1");
    }

    public function testGetRecompensaAccion2()
    {
        $estado = array('x' => 0, 'y' => 0);
        $grid[] = ["j", "v"];
        $grid[] = ["v", "T"];
        $accion = 2;
        $recompensa = getRecompensa($grid,$estado,$accion);
        $this->assertEquals(-100, $recompensa, "La recompensa deberia ser -100");
    }

    public function testGetRecompensaAccion3()
    {
        $estado = array('x' => 0, 'y' => 0);
        $grid[] = ["j", "v"];
        $grid[] = ["v", "T"];
        $accion = 3;
        $recompensa = getRecompensa($grid,$estado,$accion);
        $this->assertEquals(-1, $recompensa, "La recompensa deberia ser -1");
    }

    public function testGetRecompensaAccion0PosicionJ()
    {
        $estado = array('x' => 0, 'y' => 1);
        $grid[] = ["v", "v"];
        $grid[] = ["j", "T"];
        $accion = 0;
        $recompensa = getRecompensa($grid,$estado,$accion);
        $this->assertEquals(-100, $recompensa, "La recompensa deberia ser -100");
    }

    public function testGetRecompensaAccion1PosicionJ()
    {
        $estado = array('x' => 0, 'y' => 1);
        $grid[] = ["v", "v"];
        $grid[] = ["j", "T"];
        $accion = 1;
        $recompensa = getRecompensa($grid,$estado,$accion);
        $this->assertEquals(100, $recompensa, "La recompensa deberia ser 100");
    }

    public function testNuevaPosicionAccion0()
    {
        $estado = array('x' => 0, 'y' => 0);
        $grid[] = ["j", "v"];
        $grid[] = ["v", "T"];
        $accion = 0;
        $gridNew = nuevaPosicion($grid,$estado,$accion);
        $gridExpect[] = ["j", "v"];
        $gridExpect[] = ["v", "T"];
        $this->assertEquals($gridExpect, $gridNew, "Deberian ser el mismo array");
    }

    public function testNuevaPosicionAccion1()
    {
        $estado = array('x' => 0, 'y' => 0);
        $grid[] = ["j", "v"];
        $grid[] = ["v", "T"];
        $accion = 1;
        $gridNew = nuevaPosicion($grid,$estado,$accion);
        $gridExpect[] = ["v", "j"];
        $gridExpect[] = ["v", "T"];
        $this->assertEquals($gridExpect, $gridNew, "Deberian ser el mismo array");
    }

    public function testNuevaPosicionAccion2()
    {
        $estado = array('x' => 0, 'y' => 1);
        $grid[] = ["v", "v"];
        $grid[] = ["j", "T"];
        $accion = 2;
        $gridNew = nuevaPosicion($grid,$estado,$accion);
        $gridExpect[] = ["j", "v"];
        $gridExpect[] = ["v", "T"];
        $this->assertEquals($gridExpect, $gridNew, "Deberian ser el mismo array");
    }

    public function testNuevaPosicionAccion3()
    {
        $estado = array('x' => 0, 'y' => 0);
        $grid[] = ["j", "v"];
        $grid[] = ["v", "T"];
        $accion = 2;
        $gridNew = nuevaPosicion($grid,$estado,$accion);
        $gridExpect[] = ["j", "v"];
        $gridExpect[] = ["v", "T"];
        $this->assertEquals($gridExpect, $gridNew, "Deberian ser el mismo array");
    }

    public function testvalidarTerminadoTrueExitBoard()
    {
        $estado = array('x' => 0, 'y' => 0);
        $grid[] = ["j", "v"];
        $grid[] = ["v", "T"];
        $accion = 0;
        $result = validarTeminado($grid,$estado,$accion);
        $this->assertTrue($result, "Deberia retornar false");
    }

    public function testvalidarTerminadoTrue()
    {
        $estado = array('x' => 0, 'y' => 1);
        $grid[] = ["v", "v"];
        $grid[] = ["j", "T"];
        $accion = 1;
        $result = validarTeminado($grid,$estado,$accion);
        $this->assertTrue($result, "Deberia retornar true");
    }

    public function testvalidarTerminadoFalseAccion1()
    {
        $estado = array('x' => 0, 'y' => 0);
        $grid[] = ["j", "v"];
        $grid[] = ["v", "T"];
        $accion = 1;
        $result = validarTeminado($grid,$estado,$accion);
        $this->assertFalse($result, "Deberia retornar false");
    }

    public function testvalidarTerminadoTrueAccion3()
    {
        $estado = array('x' => 1, 'y' => 0);
        $grid[] = ["v", "j"];
        $grid[] = ["v", "T"];
        $accion = 3;
        $result = validarTeminado($grid,$estado,$accion);
        $this->assertTrue($result, "Deberia retornar true");
    }

    public function testGetNuevoEstado()
    {
        $estado = array('x' => 1, 'y' => 0);
        $grid[] = ["v", "j"];
        $grid[] = ["v", "T"];
        $accion = 3;
        $estadoResult = getNuevoEstado($grid,$estado,$accion);
        $estadoExpect = array('x' => 1, 'y' => 1);
        $this->assertEquals($estadoExpect, $estadoResult, "Deberian ser el mismo array");
    }

    public function testGetNuevoEstadoAccion1()
    {
        $estado = array('x' => 0, 'y' => 0);
        $grid[] = ["j", "v"];
        $grid[] = ["v", "T"];
        $accion = 1;
        $estadoResult = getNuevoEstado($grid,$estado,$accion);
        $estadoExpect = array('x' => 1, 'y' => 0);
        $this->assertEquals($estadoExpect, $estadoResult, "Deberian ser el mismo array");
    }

    public function testGetNuevoEstadoAccion2()
    {
        $estado = array('x' => 0, 'y' => 1);
        $grid[] = ["v", "v"];
        $grid[] = ["j", "T"];
        $accion = 2;
        $estadoResult = getNuevoEstado($grid,$estado,$accion);
        $estadoExpect = array('x' => 0, 'y' => 0);
        $this->assertEquals($estadoExpect, $estadoResult, "Deberian ser el mismo array");
    }
}
