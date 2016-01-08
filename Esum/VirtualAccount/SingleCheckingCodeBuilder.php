<?php
/**
 * 單碼檢查碼虛擬帳號生成器
 * @author Shisha <shisha@mynet.com.tw>, 20160108 Genki Co.Ltd.
 */

namespace Esum\VirtualAccount;

class SingleCheckingCodeBuilder implements AccountCheckingCodeBuilder
{
    const BASE_CHECKING_STRING = "654321987654321";
    const AMOUNT_CHECKING_STRING = "87654321";

    /**
     * 檢核帳號
     *
     * @param string 包含企業識別碼但不包含檢查碼的虛擬帳號
     * @return string 包含檢查碼的完整虛擬帳號
     */
    public function buildWithBaseChecking($account)
    {
        $p = $this->getCheckingCode($account, static::BASE_CHECKING_STRING);

        $account = "{$account}{$p}";
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
        $t1 = $this->getCheckingCode($account, static::BASE_CHECKING_STRING);
        $t2 = $this->getCheckingCode($amount, static::AMOUNT_CHECKING_STRING);
        $p = substr($t1 + $t2, -1);

        $account = "{$account}{$p}";
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
        $date = str_pad(date('zG', strtotime("+1 day", $date)), 5, 0, STR_PAD_LEFT);
        $this->checkAccountDate($account, $date);

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
        if ($len = strlen($account) > 16) {
            throw new BuildVirtualAccountFail(
                "虛擬帳號長度過長：{$account}，長度：$len（限制：16）"
            );
        }
    }
}
