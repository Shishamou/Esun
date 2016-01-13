<?php
/**
 * 單碼檢查碼虛擬帳號生成器測試元件
 * @author Shisha <shisha@mynet.com.tw>, 20160108 Genki Co.Ltd.
 */

use Esun\VirtualAccount\DoubleCheckingCodeBuilder;

class DoubleCheckingCodeBuilderTest extends PHPUnit_Framework_TestCase
{
    protected $builder;

    protected function setUp()
    {
        $this->builder = new DoubleCheckingCodeBuilder();
    }

    public function testBuildWithBaseChecking()
    {
        $this->assertEquals(
            $this->builder->buildWithBaseChecking("99551000001"),
            "9955100000186"
        );
    }

    public function testBuildWithAmountChecking()
    {
        $this->assertEquals(
            $this->builder->buildWithAmountChecking("99551000001", 1500),
            "9955100000144"
        );
    }

    public function testBuildWithAmountAndDateChecking()
    {
        $this->assertEquals(
            $this->builder->buildWithAmountAndDateChecking("99551011901", 1500, strtotime('20160119')),
            "9955101190115"
        );
    }

    public function testBuildWithAmountAndDateTimeChecking()
    {
        $this->assertEquals(
            $this->builder->buildWithAmountAndDateTimeChecking("99551011901", 1500, strtotime('20160119 10:00')),
            "9955101190115"
        );
    }

}
