<?php
/**
 * 虛擬帳號檢查碼生成介面
 * @author Shisha <shisha@mynet.com.tw>, 20160108 Genki Co.Ltd.
 */

namespace Esun\VirtualAccount;

interface AccountCheckingCodeBuilder
{
    public function buildWithBaseChecking($account);
    public function buildWithAmountChecking($account, $amount);
    public function buildWithAmountAndDateChecking($account, $amount, $date);
    public function buildWithAmountAndDateTimeChecking($account, $amount, $date);
}
