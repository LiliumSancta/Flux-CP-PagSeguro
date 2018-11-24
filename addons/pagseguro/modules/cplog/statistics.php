<?php
if (!defined('FLUX_ROOT')) exit;

$this->loginRequired();
$title = 'Estatísticas do PagSeguro';

	if (empty($_POST)){
	
		$dateMin = '2000-01-01T01:00';
		$dateMax = '2100-01-01T01:00';
	} else {

		$dateMin = $_POST['dateMin'];
		$dateMax = $_POST['dateMax'];
	}
		
		if (!preg_match('/^(\d{4}(-\d{2}){2})(T| )((([0-1]?[0-9])|([2][0-3])):)?(([0-5][0-9]))$/',$dateMin)){
			$errorMessage = sprintf('Formato incorreto: YYYY-MM-DD HH:MM');
		}else if (!preg_match('/^(\d{4}(-\d{2}){2})(T| )((([0-1]?[0-9])|([2][0-3])):)?(([0-5][0-9]))$/',$dateMax)){
			$errorMessage = sprintf('Formato incorreto: YYYY-MM-DD HH:MM');
		} else {
		
		$paymentTable = Flux::config('FluxTables.PaymentTable');

		$sql  = "SELECT COUNT(id) AS total ";
		$sql .= "FROM {$server->loginDatabase}.$paymentTable WHERE payment_date >= ? AND payment_date <= ? ";
		$sth  = $server->connection->getStatement($sql);
		$sth->execute(array($dateMin, $dateMax));
		$transactionsTotal = $sth->fetch();
		
		$sql  = "SELECT COUNT(id) AS total ";
		$sql .= "FROM {$server->loginDatabase}.$paymentTable WHERE payment_date >= ? AND payment_date <= ? AND payment_status IN (0) ";
		$sth  = $server->connection->getStatement($sql);
		$sth->execute(array($dateMin, $dateMax));
		$transactionsFail = $sth->fetch();
		
		$sql  = "SELECT COUNT(id) AS total ";
		$sql .= "FROM {$server->loginDatabase}.$paymentTable WHERE payment_date >= ? AND payment_date <= ? AND payment_status IN (1, 2) ";
		$sth  = $server->connection->getStatement($sql);
		$sth->execute(array($dateMin, $dateMax));
		$transactionsWaiting = $sth->fetch();
		
		$sql  = "SELECT COUNT(id) AS total ";
		$sql .= "FROM {$server->loginDatabase}.$paymentTable WHERE payment_date >= ? AND payment_date <= ? AND payment_status IN (3, 4) ";
		$sth  = $server->connection->getStatement($sql);
		$sth->execute(array($dateMin, $dateMax));
		$transactionsApproved = $sth->fetch();
		
		$sql  = "SELECT COUNT(id) AS total ";
		$sql .= "FROM {$server->loginDatabase}.$paymentTable WHERE payment_date >= ? AND payment_date <= ? AND payment_status IN (5, 6, 7, 8, 9) ";
		$sth  = $server->connection->getStatement($sql);
		$sth->execute(array($dateMin, $dateMax));
		$transactionsDisapproved = $sth->fetch();
		
		$sql  = "SELECT SUM(payment) AS total ";
		$sql .= "FROM {$server->loginDatabase}.$paymentTable WHERE payment_date >= ? AND payment_date <= ? AND payment_status IN (3, 4) ";
		$sth  = $server->connection->getStatement($sql);
		$sth->execute(array($dateMin, $dateMax));
		$transactionValue = $sth->fetch();
		
		$sql  = "SELECT SUM(payment) AS total ";
		$sql .= "FROM {$server->loginDatabase}.$paymentTable WHERE payment_date >= ? AND payment_date <= ?";
		$sth  = $server->connection->getStatement($sql);
		$sth->execute(array($dateMin, $dateMax));
		$transactionValueFail = $sth->fetch();
		
		}

?>