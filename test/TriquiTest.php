<?php
require __DIR__ . '/../triquiUtils.php';

use PHPUnit\Framework\TestCase;

class TriquiTest extends TestCase
{
    public function testValidateVerticalTrue()
    {
        $result = validateVertical([1, 0, 0, 1, 0, 0, 1, 0, 0]);
        $this->assertTrue($result, "El resultado deberia ser verdadero para validacion Vertical");
    }

    public function testValidateVerticalFalse()
    {
        $result = validateVertical([1, 0, 0, 0, 0, 0, 1, 0, 0]);
        $this->assertFalse($result, "El resultado deberia ser falso para validacion Vertical");
    }

    public function testValidateHorizontalTrue()
    {
        $result = validateHorizontal([1, 1, 1, 0, 0, 0, 0, 0, 0]);
        $this->assertTrue($result, "El resultado deberia ser verdadero para validacion Horizontal");
    }

    public function testValidateHorizontalFalse()
    {
        $result = validateHorizontal([1, 0, 1, 0, 0, 0, 0, 0, 0]);
        $this->assertFalse($result, "El resultado deberia ser falso para validacion Horizontal");
    }

    public function testValidateDiagonalesTrue()
    {
        $result = validateDiagonales([1, 0, 0, 0, 1, 0, 0, 0, 1]);
        $this->assertTrue($result, "El resultado deberia ser verdadero para validacion Diagonal");
    }

    public function testValidateDiagonalesInversaTrue()
    {
        $result = validateDiagonales([0, 0, 1, 0, 1, 0, 1, 0, 0]);
        $this->assertTrue($result, "El resultado deberia ser verdadero para validacion Diagonal");
    }

    public function testValidateDiagonalesFalse()
    {
        $result = validateDiagonales([1, 0, 1, 0, 0, 0, 0, 0, 0]);
        $this->assertFalse($result, "El resultado deberia ser falso para validacion Diagonal");
    }

    public function testValidateMovimientoAleatorio()
    {
        $result = movimientoAleatorio([1, -1, 1, -1, 0, 0, 0, 0, 0]);
        $result = array_count_values($result);
        $this->assertEquals(3, $result[-1], "La cantidad deberia dar 3");
    }

    public function testValidateMovimientoAleatorioSinCambiosPlayer2()
    {
        $result = movimientoAleatorio([1, -1, 1, -1, 0, 0, 0, 0, 0]);
        $result = array_count_values($result);
        $this->assertEquals(2, $result[1], "La cantidad deberia dar 2");
    }

    public function testValidateMovimientoAleatorioPlayer1()
    {
        $result = movimientoAleatorio([1, -1, 1, -1, 0, 0, 0, 0, 0], $player = 1);
        $result = array_count_values($result);
        $this->assertEquals(3, $result[1], "La cantidad deberia dar 2");
    }

    public function testValidateMovimientoAleatorioSinCambiosPlayer1()
    {
        $result = movimientoAleatorio([1, -1, 1, -1, 0, 0, 0, 0, 0], $player = 1);
        $result = array_count_values($result);
        $this->assertEquals(2, $result[-1], "La cantidad deberia dar 2");
    }

    public function testGetMovimiento()
    {
        $result = getMovimiento([1, -1, 1, -1, 0, 0, 0, 0, 0], [0.5984, 0, 0, 0, 0.32544, 0, 0, 0.98574, 0]);
        $this->assertEquals([0,0,0,0,0,0,0,1,0], $result, "Deberia devolver un movimiento");
    }

    public function testGetMovimientoTwoValues()
    {
        $result = getMovimiento([1, -1, 1, -1, 0, 0, 0, 0, 0], [0.9984, 0, 0, 0, 0.32544, 0, 0, 0.98574, 0]);
        $this->assertEquals([0,0,0,0,0,0,0,1,0], $result, "Deberia devolver un movimiento");
    }

    public function testGetMovimientoFalse()
    {
        $result = getMovimiento([1, -1, 1, -1, 0, 0, 0, 0, 0], [0.6984, 0, 0.58574, 0, 0.32544, 0, 0, 0, 0]);
        $this->assertFalse($result, "El resultado deberia ser falso");
    }

    public function testEsGanadorTrue()
    {
        $result = esGanador([-1, 0, 0, -1, 0, 0, -1, 0, 0], $player = -1);
        $this->assertTrue($result, "El resultado deberia ser true");
    }

    public function testEsGanadorFalse()
    {
        $result = esGanador([1, -1, 1, 0, 0, 0, 0, 0, 0], $player = -1);
        $this->assertFalse($result, "El resultado deberia ser falso");
    }

    public function testEsGanadorPlayer2True()
    {
        $result = esGanador([-1, 0, 0, -1, 0, 0, -1, 0, 0], $player = -1);
        $this->assertTrue($result, "El resultado deberia ser true");
    }

    public function testEsGanadorPlayer2False()
    {
        $result = esGanador([1, -1, 1, 0, 0, 0, 0, -1, 0], $player = -1);
        $this->assertFalse($result, "El resultado deberia ser falso");
    }

    public function testEsEmpate()
    {
        $result = esEmpate([-1, 1, -1, -1, 1, -1, 1, -1, 1]);
        $this->assertTrue($result, "El resultado deberia ser true");
    }

    public function testEsEmpateFalse()
    {
        $result = esEmpate([0, 0, 0, 0, 0, 0, 0, 0, 0]);
        $this->assertFalse($result, "El resultado deberia ser true");
    }
}
