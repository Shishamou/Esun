<?php
/**
 * 單碼檢查碼虛擬帳號生成器測試元件
 * @author Shisha <shisha@mynet.com.tw>, 20160108 Genki Co.Ltd.
 */

use Esun\VirtualAccount\SingleCheckingCodeBuilder;

class SingleCheckingCodeBuilderTest extends PHPUnit_Framework_TestCase
{
    protected $builder;

    protected function setUp()
    {
        $this->builder = new SingleCheckingCodeBuilder();
    }

    public function testBuildWithBaseChecking()
    {
        $this->assertEquals(
            $this->builder->buildWithBaseChecking("991233939889"),
            "9912339398893"
        );
    }

    public function testBuildWithAmountChecking()
    {
        $this->assertEquals(
            $this->builder->buildWithAmountChecking("991233939889", 1500),
            "9912339398892"
        );
    }

    public function testBuildWithAmountAndDateChecking()
    {
        $this->assertEquals(
            $this->builder->buildWithAmountAndDateChecking("991230119001", 1500, strtotime('20160119')),
            "9912301190015"
        );
    }

    public function testBuildWithAmountAndDateTimeChecking()
    {
        $this->assertEquals(
            $this->builder->buildWithAmountAndDateTimeChecking("991232131089", 1500, strtotime('20160731 10:00')),
            "9912321310891"
        );
    }
}
