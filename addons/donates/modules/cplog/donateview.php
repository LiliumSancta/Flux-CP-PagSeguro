<?php if (!defined('FLUX_ROOT')) exit;
$this->loginRequired();

require_once Flux::config('PagSeguroLib');
$title = 'Visualizando Transação';

$donateTable = Flux::config('FluxTables.DonateTable');
$donateId = (int)$params->get('id');
$donateRef = trim($params->get('payment_id'));

$sql = "SELECT * FROM {$server->loginDatabase}.$donateTable WHERE id = ? OR payment_id = ?";
$sth = $server->connection->getStatement($sql);
$sth->execute(array($donateId,$donateRef));
$transaction = $sth->fetch();

if ($transaction->pagseguro_code){
	$transactionCredencial = new PagSeguroAccountCredentials(Flux::config('EmailPagSeguro'), Flux::config('TokenPagseguro'));
	$transactionpagseguro  = PagSeguroNotificationService::checkTransaction($transactionCredencial, $transaction->pagseguro_code);
}

?>