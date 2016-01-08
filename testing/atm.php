<?php
/**
 *
 */

namespace Esun;

use Exception;

class Atm
{
	const ACCOUNT_CHECKING_CODE = "654321987654321";
	const AMOUNT_CHECKING_CODE = "87654321";
	

	/**
	 * 企業識別碼
	 *
	 * @var string max_size=5
	 */
	protected $oid;

	/**
	 * 繳費金額
	 *
	 * @var integer max_size=8
	 */
	protected $amount;

	/**
	 * 繳費期限
	 *
	 * @var date
	 */
	protected $expireDate;

	function __construct()
	{
		# code...
	}

	public function setOid($oid)
	{
		return $this->oid = $this->parseOid($oid);
	}

	private function parseOid($oid)
	{
		$maxSize = 5;
		$oid = str_pad($oid, $maxSize, '0', STR_PAD_LEFT);
		if (strlen($oid) > $maxSize) {
			throw new Exception("Oid 長度限制為{$maxSize}字元：{$oid}", 1);
		}

		return $oid;
	}

	public function setAmount($amount)
	{
		return $this->amount = $this->parseAmount($amount);
	}

	private function parseAmount($amount)
	{
		$maxSize = 8;
		$amount = str_pad($amount, $maxSize, '0', STR_PAD_LEFT);
		if (strlen($amount) > $maxSize) {
			throw new Exception("Amount 長度限制為{$maxSize}位數：{$amount}", 1);
		}

		return $amount;
	}

	public function makeVirtualAccount($oid, $amount, $expireDate)
	{
	}



	private function getCheckCode($virtualAccount)
	{
	}
}
