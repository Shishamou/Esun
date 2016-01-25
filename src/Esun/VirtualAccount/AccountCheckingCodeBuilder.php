<?php
/**
 * 虛擬帳號檢查碼生成介面
 * @author Shisha <shisha@mynet.com.tw>, 20160108 Genki Co.Ltd.
 */

namespace Esun\VirtualAccount;

interface AccountCheckingCodeBuilder
{
    /**
     * 取得虛擬帳號預留長度
     *
     * @return integer
     */
    public function getReservedLength();

    /**
     * 檢核帳號
     *
     * @param string 包含企業識別碼但不包含檢查碼的虛擬帳號
     * @return string 包含檢查碼的完整虛擬帳號
     */
    public function buildWithBaseChecking($account);

    /**
     * 檢核帳號及金額
     *
     * @param string 包含企業識別碼但不包含檢查碼的虛擬帳號
     * @param integer 應繳金額（最大長度為八位數）
     * @return string 包含檢查碼的完整虛擬帳號
     */
    public function buildWithAmountChecking($account, $amount);

    /**
     * 檢核帳號、金額及繳款截止日
     *
     * @param string 包含企業識別碼但不包含檢查碼的虛擬帳號
     * @param integer 應繳金額（最大長度為八位數）
     * @param integer 繳費期限之時間戳記
     * @return string 包含檢查碼的完整虛擬帳號
     */
    public function buildWithAmountAndDateChecking($account, $amount, $date);

    /**
     * 檢核帳號、金額及繳款截止小時
     *
     * @param string 包含企業識別碼但不包含檢查碼的虛擬帳號
     * @param integer 應繳金額（最大長度為八位數）
     * @param integer 繳費期限之時間戳記
     * @return string 包含檢查碼的完整虛擬帳號
     */
    public function buildWithAmountAndDateTimeChecking($account, $amount, $date);
}
