<?php
/**
 * 單碼檢查碼虛擬帳號生成器
 * @author Shisha <shisha@mynet.com.tw>, 20160108 Genki Co.Ltd.
 */

namespace Esun\VirtualAccount;

use Esun\VirtualAccount\BuildVirtualAccountFail as Exception;
use Esun\VirtualAccount\CheckingCodeBuilder\SingleCheckingCodeBuilder;
use Esun\VirtualAccount\CheckingCodeBuilder\DoubleCheckingCodeBuilder;

class VirtualAccountBuilder
{
    // =========================================================================
    // = 設定
    // =========================================================================
    const COMPANY_ID_SIZE  = 5;
    const ACCOUNT_SIZE_MIN = 11;
    const ACCOUNT_SIZE_MAX = 16;
    const AMOUNT_SIZE_MAX = 8;

    // =========================================================================
    // = 檢查碼類型
    // =========================================================================
    const SINGLE_BASE_CHECKING                 = 1;
    const SINGLE_AMOUNT_CHECKING               = 2;
    const SINGLE_AMOUNT_AND_DATE_CHECKING      = 3;
    const SINGLE_AMOUNT_AND_DATE_TIME_CHECKING = 4;
    const DOUBLE_BASE_CHECKING                 = 5;
    const DOUBLE_AMOUNT_CHECKING               = 6;
    const DOUBLE_AMOUNT_AND_DATE_CHECKING      = 7;

    // =========================================================================
    // = 屬性
    // =========================================================================
    protected $companyId;
    protected $accountSize;
    protected $checkingCodeType;

    protected $checkingCodeBuilder;


    // =========================================================================
    // = 建構
    // =========================================================================

    public function __construct($companyId, $accountSize, $checkingCodeType)
    {
        $this->setCompanyId($companyId);
        $this->setAccountSize($accountSize);
        $this->setCheckingCodeType($checkingCodeType);
    }

    /**
     * 企業識別碼
     */
    public function setCompanyId($companyId)
    {
        $maxSize = static::COMPANY_ID_SIZE;
        if (strlen($companyId) != $maxSize)
            throw new Exception(
                "企業識別碼必須為 {$maxSize} 位數數字。",
                Exception::COMPANY_ID_WRONG_SIZE
            );

        $this->companyId = $companyId;
    }

    /**
     * 設定虛擬帳號總長度
     */
    public function setAccountSize($accountSize)
    {
        $minSize = static::ACCOUNT_SIZE_MIN;
        $maxSize = static::ACCOUNT_SIZE_MAX;
        if ($accountSize > $maxSize || $accountSize < $minSize)
            throw new Exception(
                "虛擬帳號長度為 {$minSize} ~ {$maxSize} 碼。",
                Exception::ACCOUNT_WRONG_SIZE
            );

        $this->accountSize = $accountSize;
    }

    /**
     * 設定檢查碼類型
     */
    public function setCheckingCodeType($checkingCodeType)
    {
        if ( ! in_array($checkingCodeType, [
            static::SINGLE_BASE_CHECKING,
            static::SINGLE_AMOUNT_CHECKING,
            static::SINGLE_AMOUNT_AND_DATE_CHECKING,
            static::SINGLE_AMOUNT_AND_DATE_TIME_CHECKING,
            static::DOUBLE_BASE_CHECKING,
            static::DOUBLE_AMOUNT_CHECKING,
            static::DOUBLE_AMOUNT_AND_DATE_CHECKING
        ])) {
            throw new Exception(
                "未定義檢查碼格式",
                Exception::UNKNOW_CHECKING_CODE_TYPE
            );
        }

        $this->checkingCodeType = $checkingCodeType;
    }

    // =========================================================================
    // = 生成
    // =========================================================================

    /**
     * 生成虛擬帳號
     *
     * @param integer 編號
     * @param integer 繳費金額
     * @param integer 時間戳記
     * @return string 包含檢查碼的完整虛擬帳號
     */
    public function make($number, $amount = null, $date = null)
    {
        // 處理參數
        $amount = ($amount)? $this->parseAmount($amount) : $amount;
        $date = ($date)? $this->parseDate($date) : $date;

        // 初始化驗整碼產生器
        $this->initialCheckingCodeBuilder();

        // 產生虛擬帳號
        $writeOffNumber = $this->makeWriteOffNumber($number, $date);
        $account = "{$this->companyId}{$writeOffNumber}";

        // 產生包含檢查碼之虛擬帳號
        $account = $this->makeAccountWithCheckingCode($account, $amount, $date);

        // 驗證
        if (($len = strlen($account)) != $this->accountSize) {
            throw new Exception(
                "生成失敗",
                Exception::PRODUCT_INCOMPATIBLE
            );
        }

        return $account;
    }

    /**
     * 處理金額
     *
     * @param integer 繳費金額
     * @return integer 繳費金額
     * @throws BuildVirtualAccountFail
     */
    public function parseAmount($amount)
    {
        $maxSize = static::AMOUNT_SIZE_MAX;
        if ((integer)$amount <= 0 || strlen($amount) > $maxSize)
            throw new Exception(
                "金額必須大於0，最大為 {$maxSize} 位數之整數。",
                Exception::AMOUNT_WRONG_SIZE
            );

        return $amount;
    }

    /**
     * 處理日期
     *
     * @param integer 時間戳記
     * @return integer 時間戳記
     * @throws BuildVirtualAccountFail
     */
    public function parseDate($date)
    {
        return $date;
    }

    /**
     * 初始化檢查碼生成器
     *
     * @return AccountCheckingCodeBuilder
     * @throws BuildVirtualAccountFail
     */
    private function initialCheckingCodeBuilder()
    {
        switch ($this->checkingCodeType) {
            case static::SINGLE_BASE_CHECKING:
            case static::SINGLE_AMOUNT_CHECKING:
            case static::SINGLE_AMOUNT_AND_DATE_CHECKING:
            case static::SINGLE_AMOUNT_AND_DATE_TIME_CHECKING:
                return $this->checkingCodeBuilder = new SingleCheckingCodeBuilder();

            case static::DOUBLE_BASE_CHECKING:
            case static::DOUBLE_AMOUNT_CHECKING:
            case static::DOUBLE_AMOUNT_AND_DATE_CHECKING:
                return $this->checkingCodeBuilder = new DoubleCheckingCodeBuilder();

            default:
                throw new Exception(
                    "未定義檢查碼格式",
                    Exception::UNSET_CHECKING_CODE_TYPE
                );
        }
    }

    /**
     * 產生銷帳編號
     *
     * @param string 編號
     * @param integer 時間戳記
     * @return string 銷帳編號
     * @throws BuildVirtualAccountFail
     */
    public function makeWriteOffNumber($number, $date)
    {
        // 計算銷帳編號長度
        $reservedLength = $this->checkingCodeBuilder->getReservedLength();
        $length = $this->accountSize - strlen($this->companyId) - $reservedLength;

        switch ($this->checkingCodeType) {
            case static::SINGLE_BASE_CHECKING:
            case static::DOUBLE_BASE_CHECKING:
            case static::SINGLE_AMOUNT_CHECKING:
            case static::DOUBLE_AMOUNT_CHECKING:
                return static::parseNumberLength($number, $length);

            case static::SINGLE_AMOUNT_AND_DATE_CHECKING:
            case static::DOUBLE_AMOUNT_AND_DATE_CHECKING:
                $date = date('md', $date);
                $length -= strlen($date);
                $number = static::parseNumberLength($number, $length);
                return "{$date}{$number}";

            case static::SINGLE_AMOUNT_AND_DATE_TIME_CHECKING:
                $date = date('zG', strtotime("+1 day", $date));
                $length -= strlen($date);
                $number = static::parseNumberLength($number, $length);
                return "{$date}{$number}";

            default:
                throw new Exception(
                    "未定義檢查碼格式",
                    Exception::UNSET_CHECKING_CODE_TYPE
                );
        }
    }

    /**
     * 處理編號長度
     *
     * @param string 編號
     * @param integer 長度
     * @return string
     */
    private static function parseNumberLength($number, $length)
    {
        $number = substr($number, -$length);
        $number = str_pad($number, $length, 0, STR_PAD_LEFT);
        return $number;
    }

    /**
     * 產生包含檢查碼之完整虛擬帳號
     *
     * @param string 包含企業識別碼但不包含檢查碼的虛擬帳號
     * @param integer 應繳金額
     * @param integer 時間戳記
     * @return string 虛擬帳號
     * @throws BuildVirtualAccountFail
     */
    private function makeAccountWithCheckingCode($account, $amount, $date)
    {
        $builder = $this->checkingCodeBuilder;

        switch ($this->checkingCodeType) {
            case static::SINGLE_BASE_CHECKING:
            case static::DOUBLE_BASE_CHECKING:
                return $builder->buildWithBaseChecking($account);

            case static::SINGLE_AMOUNT_CHECKING:
            case static::DOUBLE_AMOUNT_CHECKING:
                return $builder->buildWithAmountChecking($account, $amount);

            case static::SINGLE_AMOUNT_AND_DATE_CHECKING:
            case static::DOUBLE_AMOUNT_AND_DATE_CHECKING:
                return $builder->buildWithAmountAndDateChecking($account, $amount, $date);

            case static::SINGLE_AMOUNT_AND_DATE_TIME_CHECKING:
                return $builder->buildWithAmountAndDateTimeChecking($account, $amount, $date);

            default:
                throw new Exception("未定義檢查碼格式", Exception::UNSET_CHECKING_CODE_TYPE);
        }
    }
}
