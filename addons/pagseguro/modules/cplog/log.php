<?php
if (!defined('FLUX_ROOT')) exit;

$this->loginRequired();
$title = 'Transações do PagSeguro';
$paymentTable = Flux::config('FluxTables.PaymentTable');

$paymentId         = (int)$params->get('id');
$paymentCode       = trim($params->get('payment_code'));
$paymentRef        = trim($params->get('payment_id'));
$paymentAcc        = (int)$params->get('account_id');
$paymentUser       = trim($params->get('userid'));
$paymentIp         = trim($params->get('payment_ip'));
$paymentDateMin    = $params->get('dateMin');
$paymentDateMax    = $params->get('dateMax');
$paymentVal        = str_replace(',','.',$params->get('payment'));
$paymentEmail      = $params->get('email');
$paymentStatus     = $params->get('payment_status');
$paymentStatusLog  = $params->get('payment_status_log');

$sqlpartial    = "WHERE 1=1 ";
$bind          = array();

if ($paymentId) {
	$sqlpartial .= 'AND id = ? ';
	$bind[]      = $paymentId;
}

if ($paymentCode) {
	$sqlpartial .= 'AND payment_code = ? ';
	$bind[]      = $paymentCode;
}

if ($paymentRef) {
	$sqlpartial .= 'AND payment_id = ? ';
	$bind[]      = $paymentRef;
}

if ($paymentAcc) {
	$sqlpartial .= 'AND account_id = ? ';
	$bind[]      = $paymentAcc;
}

if ($paymentUser) {
	$sqlpartial .= 'AND userid LIKE ? ';
	$bind[]      = "%$paymentUser%";
}

if ($paymentIp) {
	$sqlpartial .= 'AND payment_ip LIKE ? ';
	$bind[]      = "%$paymentIp%";
}

if ($paymentVal) {
	$sqlpartial .= 'AND payment = ? ';
	$bind[]      = $paymentVal;
}

if ($paymentEmail) {
	$sqlpartial .= 'AND email = ? ';
	$bind[]      = $paymentEmail;
}

if ($paymentDateMin && $paymentDateMax) {
	$sqlpartial .= 'AND payment_date >= ? AND payment_date <= ? ';
	$bind[]      = $paymentDateMin;
	$bind[]      = $paymentDateMax;
}

if ($paymentStatusLog != '') {
	$sqlpartial .= 'AND payment_status = ? ';
	$bind[]      = $paymentStatusLog;
}

if ($paymentStatus == 1) {
	$sqlpartial .= 'AND payment_status IN (0) ';
} elseif ($paymentStatus == 2){
	$sqlpartial .= 'AND payment_status IN (1, 2) ';
} elseif ($paymentStatus == 3){
	$sqlpartial .= 'AND payment_status IN (3, 4) ';
} elseif ($paymentStatus == 4){
	$sqlpartial .= 'AND payment_status IN (5, 6, 7, 8, 9) ';
}

$sql = "SELECT COUNT(id) AS total FROM {$server->loginDatabase}.$paymentTable $sqlpartial";
$sth = $server->connection->getStatement($sql);
$sth->execute($bind);

$paginator = $this->getPaginator($sth->fetch()->total);
$paginator->setSortableColumns(array(
	'id' => 'desc',
	'account_id',
	'payment_id',
	'payment_date',
	'payment_status',
	'payment'
));

$sql = "SELECT id, account_id, payment_id, userid, payment_ip, payment_date, payment, email, payment_status FROM {$server->loginDatabase}.$paymentTable $sqlpartial";
$sql = $paginator->getSQL($sql);
$sth = $server->connection->getStatement($sql);
$sth->execute($bind);

$transactions = $sth->fetchAll();
?>