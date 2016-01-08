<?php
require '../bootstrap.php';

use Esum\VirtualAccount\SingleCheckingCodeBuilder;

$builder = new SingleCheckingCodeBuilder;

echo "<pre>", var_export([
	$builder->buildWithBaseChecking('991233939889'),
	'9912339398893',
	'991233939889',
], 1), "</pre>";

echo "<pre>", var_export([
	$builder->buildWithAmountChecking('991233939889', 1500),
	'9912339398892',
	'991233939889 + 1500',
], 1), "</pre>";

echo "<pre>", var_export([
	$builder->buildWithAmountAndDateChecking('991230119001', 1500, strtotime('20160119')),
	'9912301190015',
	'991230119001 + 1500 + 20150119',
], 1), "</pre>";

echo "<pre>", var_export([
	$builder->buildWithAmountAndDateTimeChecking('991232131089', 1500, strtotime('20160731 10:00')),
	'9912321310891',
	'991232131089 + 1500 + 20150119',
], 1), "</pre>";
