<?php
/**
 * 雙碼檢查碼虛擬帳號生成器
 * @author Shisha <shisha@mynet.com.tw>, 20160108 Genki Co.Ltd.
 */

namespace Esun\VirtualAccount;

class DoubleCheckingCodeBuilder implements AccountCheckingCodeBuilder
{
    const BASE_CHECKING_STRING_PART_A = "31731731731731";
    const BASE_CHECKING_STRING_PART_B = "73973973973973";
    const AMOUNT_CHECKING_STRING_PART_A = "31731731";
    const AMOUNT_CHECKING_STRING_PART_B = "73973973";

    /**
     * 檢核帳號
     *
     * @param string 包含企業識別碼但不包含檢查碼的虛擬帳號
     * @return string 包含檢查碼的完整虛擬帳號
     */
    public function buildWithBaseChecking($account)
    {
        $o = $this->getCheckingCode($account, static::BASE_CHECKING_STRING_PART_A);
        $p = $this->getCheckingCode($account, static::BASE_CHECKING_STRING_PART_B);

        $account = "{$account}{$o}{$p}";
        $this->checkAccountLength($account);
        return $account;
    }

    /**
     * 檢核帳號及金額
     *
     * @param string 包含企業識別碼但不包含檢查碼的虛擬帳號
     * @param integer 應繳金額（最大長度為八位數）
     * @return string 包含檢查碼的完整虛擬帳號
     */
    public function buildWithAmountChecking($account, $amount)
    {
        $o1 = $this->getCheckingCode($account, static::BASE_CHECKING_STRING_PART_A);
        $o2 = $this->getCheckingCode($amount, static::AMOUNT_CHECKING_STRING_PART_A);
        $o = substr($o1 + $o2, -1);

        $p1 = $this->getCheckingCode($account, static::BASE_CHECKING_STRING_PART_B);
        $p2 = $this->getCheckingCode($amount, static::AMOUNT_CHECKING_STRING_PART_B);
        $p = substr($p1 + $p2, -1);

        $account = "{$account}{$o}{$p}";
        $this->checkAccountLength($account);
        return $account;
    }

    /**
     * 檢核帳號、金額及繳款截止日
     *
     * @param string 包含企業識別碼但不包含檢查碼的虛擬帳號
     * @param integer 應繳金額（最大長度為八位數）
     * @param integer 繳費期限之時間戳記
     * @return string 包含檢查碼的完整虛擬帳號
     */
    public function buildWithAmountAndDateChecking($account, $amount, $date)
    {
        $this->checkAccountDate($account, date('md', $date));

        return $this->buildWithAmountChecking($account, $amount);
    }

    /**
     * 檢核帳號、金額及繳款截止小時
     *
     * @param string 包含企業識別碼但不包含檢查碼的虛擬帳號
     * @param integer 應繳金額（最大長度為八位數）
     * @param integer 繳費期限之時間戳記
     * @return string 包含檢查碼的完整虛擬帳號
     */
    public function buildWithAmountAndDateTimeChecking($account, $amount, $date)
    {
        return $this->buildWithAmountChecking($account, $amount);
    }

    /**
     * 檢核帳號、金額及繳款截止小時
     *
     * @param string 包含企業識別碼但不包含檢查碼的虛擬帳號
     * @param integer 應繳金額（最大長度為八位數）
     * @param integer 繳費期限之時間戳記
     * @return string 包含檢查碼的完整虛擬帳號
     */
    private function getCheckingCode($current, $checkingString)
    {
        $current = str_pad($current, strlen($checkingString), 0, STR_PAD_LEFT);

        $t = array_map(function($a, $b) {
            return $a * $b;
        }, str_split($current), str_split($checkingString));

        $t = array_sum($t);
        return substr($t, -1);
    }

    /**
     * 檢驗帳號之銷帳編號日期時間
     *
     * @param string 要被檢查的虛擬帳號
     * @param string 時間字串
     * @throws BuildVirtualAccountFail
     */
    private function checkAccountDate($account, $dateString)
    {
        $accountDate = substr($account, 5, strlen($dateString));
        if ($accountDate !== $dateString) {
            throw new BuildVirtualAccountFail(
                "輸入日期與銷帳編號不符；輸入日期：{$dateString}，銷帳編號日期：{$accountDate}"
            );
        }
    }

    /**
     * 檢驗虛擬帳號長度
     *
     * @param string 要被檢查的虛擬帳號
     * @throws BuildVirtualAccountFail
     */
    private function checkAccountLength($account)
    {
        if ($len = strlen($account) > 17) {
            throw new BuildVirtualAccountFail(
                "虛擬帳號長度過長：{$account}，長度：$len（限制：17）"
            );
        }
    }
}
