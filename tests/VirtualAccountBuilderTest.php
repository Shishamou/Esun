<?php
/**
 * 單碼檢查碼虛擬帳號生成器測試元件
 * @author Shisha <shisha@mynet.com.tw>, 20160108 Genki Co.Ltd.
 */

use Esun\VirtualAccount\VirtualAccountBuilder as Builder;
use Esun\VirtualAccount\BuildVirtualAccountFail;

class VirtualAccountBuilderTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
    }

    public function testMakeSingle()
    {
        $builder = new Builder("99123", 13, Builder::SINGLE_BASE_CHECKING);
        $this->assertEquals(
            $builder->make("3939889"),
            "9912339398893"
        );

        $builder = new Builder("99123", 13, Builder::SINGLE_AMOUNT_CHECKING);
        $this->assertEquals(
            $builder->make("3939889", 1500),
            "9912339398892"
        );

        $builder = new Builder("99123", 13, Builder::SINGLE_AMOUNT_AND_DATE_CHECKING);
        $this->assertEquals(
            $builder->make("991230119001", 1500, strtotime('20160119')),
            "9912301190015"
        );

        $builder = new Builder("99123", 13, Builder::SINGLE_AMOUNT_AND_DATE_TIME_CHECKING);
        $this->assertEquals(
            $builder->make("991232131089", 1500, strtotime('20160731 10:00')),
            "9912321310891"
        );
    }

    public function testMakeDouble()
    {
        $builder = new Builder("99551", 13, Builder::DOUBLE_BASE_CHECKING);
        $this->assertEquals(
            $builder->make("000001"),
            "9955100000186"
        );

        $builder = new Builder("99551", 13, Builder::DOUBLE_AMOUNT_CHECKING);
        $this->assertEquals(
            $builder->make("000001", 1500),
            "9955100000144"
        );

        $builder = new Builder("99551", 13, Builder::DOUBLE_AMOUNT_AND_DATE_CHECKING);
        $this->assertEquals(
            $builder->make("1011901", 1500, strtotime('20160119')),
            "9955101190115"
        );
    }

    /**
     * @expectedException Esun\VirtualAccount\BuildVirtualAccountFail
     * @expectedExceptionCode Esun\VirtualAccount\BuildVirtualAccountFail::COMPANY_ID_WRONG_SIZE
     */
    public function testConstructCompanyIdSizeWrong()
    {
        $builder = new Builder("123456", 13, Builder::SINGLE_BASE_CHECKING);
    }

    /**
     * @expectedException Esun\VirtualAccount\BuildVirtualAccountFail
     * @expectedExceptionCode Esun\VirtualAccount\BuildVirtualAccountFail::ACCOUNT_WRONG_SIZE
     */
    public function testConstructFailAccountSizeWrong()
    {
        $builder = new Builder("12345", 17, Builder::SINGLE_BASE_CHECKING);
    }

    /**
     * @expectedException Esun\VirtualAccount\BuildVirtualAccountFail
     * @expectedExceptionCode Esun\VirtualAccount\BuildVirtualAccountFail::UNKNOW_CHECKING_CODE_TYPE
     */
    public function testConstructFailUnknowCheckingCodeType()
    {
        $builder = new Builder("12345", 13, 999);
    }
}
