<?php
/**
 * 虛擬帳號生成失敗錯誤類別
 * @author Shisha <shisha@mynet.com.tw>, 20160108 Genki Co.Ltd.
 */

namespace Esun\VirtualAccount;

use Exception;

class BuildVirtualAccountFail extends Exception
{
    const COMPANY_ID_WRONG_SIZE          = 11;
    const ACCOUNT_WRONG_SIZE             = 12;
    const UNKNOW_CHECKING_CODE_TYPE      = 13;
    const UNSET_CHECKING_CODE_TYPE       = 15;
    const AMOUNT_WRONG_SIZE              = 16;
    const PRODUCT_INCOMPATIBLE           = 19;

    const CHECKING_ACCOUNT_DATE_FAIL     = 21;
}
