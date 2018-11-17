<?php if (!defined('FLUX_ROOT')) exit;

require_once Flux::config('PagSeguroLib');
$donateTable = Flux::config('FluxTables.DonateTable');
$tableBan    = Flux::config('FluxTables.AccountBanTable');
$donatePromo = Flux::config('Promotion');
$initPromo   = Flux::config('InitPromo');
$donateVar   = Flux::config('PagSeguroVar');
$donateFlux  = Flux::config('PagSeguroFlux');
$rate        = Flux::config('rate');

if($_SERVER['REQUEST_METHOD'] == 'POST'){

	if (count($_POST) && isset($_POST['notificationType']) && isset($_POST['notificationCode'])){
	
		$notificationType   = $_POST['notificationType'];
		$notificationCode = $_POST['notificationCode'];
			
		$transactionCredencial = new PagSeguroAccountCredentials(Flux::config('EmailPagSeguro'), Flux::config('TokenPagseguro'));
		$transaction  = PagSeguroNotificationService::checkTransaction($transactionCredencial, $notificationCode);
			
		$donateStatus = $transaction->getStatus()->getValue();
		$donateRef    = $transaction->getReference();
		$donateCode   = $transaction->getCode();
			
		$sql  = "SELECT account_id, payment, pagseguro_status ";
		$sql .= "FROM {$server->loginDatabase}.$donateTable WHERE payment_id = ?";
		$sth = $server->connection->getStatement($sql);
		$sth->execute(array($donateRef));
		$donate     = $sth->fetch();
		$account    = $donate->account_id;
		$donateVal  = $donate->payment;
		$status     = $donate->pagseguro_status;
			
		if ($donateStatus == $status)
			exit;		
		
		switch ($donateStatus){
			
			case 1:
				$sql  = "UPDATE {$server->loginDatabase}.$donateTable ";
				$sql .= "SET payment_status = ?, pagseguro_code = ?, pagseguro_status = ?, ";
				$sql .= "pagseguro_id = ? WHERE payment_id = ?";
				$sth  = $server->connection->getStatement($sql);
				$sth->execute(array(1, $notificationCode, $donateStatus, $donateCode, $donateRef));
			break;
				
			case 2:
				$sql  = "UPDATE {$server->loginDatabase}.$donateTable ";
				$sql .= "SET payment_status = ?, pagseguro_code = ?, pagseguro_status = ?, ";
				$sql .= "pagseguro_id = ? WHERE payment_id = ?";
				$sth  = $server->connection->getStatement($sql);
				$sth->execute(array(1, $notificationCode, $donateStatus, $donateCode, $donateRef));
			break;
				
			case 3:
				$credits = floor(($donateVal >= $initPromo ? ($donateVal + ($donatePromo*$donateVal)/100) : $donateVal) / $rate);
				
				if ($donateFlux){
					$server->loginServer->depositCredits($account, $credits, $donateVal);
				} 
				else{
					$sql  = "SELECT COUNT(account_id) AS varExists FROM {$server->charMapDatabase}.global_reg_value ";
					$sql .= "WHERE account_id = ? AND str = ?";
					$sth  = $server->connection->getStatement($sql);
					$sth->execute(array($account , $donateVar));
					$cash = $sth->fetch()->varExists;
					
					if ($cash){
						$sql  = "UPDATE {$server->charMapDatabase}.global_reg_value SET value = value + ? ";
						$sql .= "WHERE account_id = ? AND str = ?";
						$sth  = $server->connection->getStatement($sql);
						$sth->execute(array($credits ,$account,  $donateVar));
					}
					else{
						$sql  = "INSERT INTO {$server->charMapDatabase}.global_reg_value (char_id, str, value, type, account_id) ";
						$sql .= "VALUES (?, ?, ?, ?, ?)";
						$sth  = $server->connection->getStatement($sql);
						$sth->execute(array(0, $donateVar, $credits, 2, $account));
					}
				}
		
				$sql  = "UPDATE {$server->loginDatabase}.$donateTable ";
				$sql .= "SET payment_status = ?, pagseguro_status = ?, ";
				$sql .= "pagseguro_id = ? WHERE payment_id = ?";
				$sth  = $server->connection->getStatement($sql);
				$sth->execute(array(2 , $donateStatus, $donateCode, $donateRef));
			break;
		
			case 4:
				$sql  = "UPDATE {$server->loginDatabase}.$donateTable ";
				$sql .= "SET payment_status = ?, pagseguro_status = ?, ";
				$sql .= "WHERE payment_id = ?";
				$sth  = $server->connection->getStatement($sql);
				$sth->execute(array(2 , $donateStatus, $donateRef));
			break;
			
			case 5:
				$sql  = "INSERT INTO {$server->loginDatabase}.$tableBan (account_id, banned_by, ban_type, ban_until, ban_date, ban_reason) ";
				$sql .= "VALUES (?, ?, 2, '0000-00-00 00:00:00', NOW(), ?)";
				$sth  = $server->connection->getStatement($sql);
				$sth->execute(array($account, 'PagSeguro', 'Tentativa de fraude ao sistema do PagSeguro: O doador abriu uma disputa após recebimento dos créditos e está banido.'));
				
				$sql  = "UPDATE {$server->loginDatabase}.login SET state = 5, unban_time = 0 WHERE account_id = ?";
				$sth  = $server->connection->getStatement($sql);
				$sth->execute(array($account));
			break;

			case 6:
				$sql  = "INSERT INTO {$server->loginDatabase}.$tableBan (account_id, banned_by, ban_type, ban_until, ban_date, ban_reason) ";
				$sql .= "VALUES (?, ?, 2, '0000-00-00 00:00:00', NOW(), ?)";
				$sth  = $server->connection->getStatement($sql);
				$sth->execute(array($account, 'PagSeguro', 'Tentativa de fraude ao sistema do PagSeguro: O doador recebeu novamente seu dinheiro e está banido.'));
				
				$sql  = "UPDATE {$server->loginDatabase}.login SET state = 5, unban_time = 0 WHERE account_id = ?";
				$sth  = $server->connection->getStatement($sql);
				$sth->execute(array($account));
			break;
			
			case 7:
				$sql  = "UPDATE {$server->loginDatabase}.$donateTable ";
				$sql .= "SET payment_status = ?, pagseguro_status = ? ";
				$sql .= "WHERE payment_id = ?";
				$sth  = $server->connection->getStatement($sql);
				$sth->execute(array(3 , $donateStatus, $donateRef));
			break;
		}
	}
}
?>