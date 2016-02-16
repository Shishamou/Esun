<?php

require "Esun/src/autoload.php";

use Esun\VirtualAccount\VirtualAccountBuilder;

$builder = new VirtualAccountBuilder(
	// 企業識別碼
	12345,
	// 虛擬帳號長度
	14,
	// 檢查碼類型
	VirtualAccountBuilder::SINGLE_BASE_CHECKING
);

$va = $builder->make(
	// 銷帳編號
	0000001,
	// 繳費金額
	1000,
	// 繳費期限
	strtotime('20160101')
);

echo $va;
