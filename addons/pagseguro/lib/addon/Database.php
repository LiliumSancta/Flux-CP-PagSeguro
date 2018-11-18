<?php

namespace PagSeguro\addon;

class Database{

	protected $server;
	protected $paymentTable;
	protected $banTable;
	protected $transactionCode;
	protected $paymentStatus;
	protected $paymentRef;
	protected $paymentVar;

	protected $paymentAcc;
	protected $paymentUser;
	protected $paymentEmail;
	protected $paymentIp;
	protected $paymentBy;
	protected $paymentVal;

	public function __construct($server){
		$this->setServer($server);
	}

	public function setNewPaymentParams(
		$paymentAcc,
		$paymentUser,
		$paymentEmail,
		$paymentRef,
		$paymentIp,
		$paymentBy,
		$paymentVal, 
		$paymentTable
	){
		$this->setPaymentAcc($paymentAcc);
		$this->setPaymentUser($paymentUser);
		$this->setPaymentEmail($paymentEmail);
		$this->setPaymentRef($paymentRef);
		$this->setPaymentIp($paymentIp);
		$this->setPaymentBy($paymentBy);
		$this->setPaymentVal($paymentVal);
		$this->setPaymentTable($paymentTable);
	}

	public function setUpdateParams($paymentTable, $banTable, $paymentRef){
		$this->setPaymentTable($paymentTable);
		$this->setBanTable($banTable);
		$this->setPaymentRef($paymentRef);
	}

	protected function getServer(){
		return $this->server;
	}

	protected function setServer($server){
		$this->server = $server;
	}

	protected function getPaymentTable(){
		return $this->paymentTable;
	}

	protected function setPaymentTable($paymentTable){
		$this->paymentTable = $paymentTable;
	}

	protected function getBanTable(){
		return $this->banTable;
	}

	protected function setBanTable($banTable){
		$this->banTable = $banTable;
	}

	protected function getTransactionCode(){
		return $this->transactionCode;
	}

	protected function setTransactionCode($transactionCode){
		$this->transactionCode = $transactionCode;
	}

	protected function getPaymentStatus(){
		return $this->paymentStatus;
	}

	protected function setPaymentStatus($paymentStatus){
		$this->paymentStatus = $paymentStatus;
	}

	protected function getPaymentRef(){
		return $this->paymentRef;
	}

	protected function setPaymentRef($paymentRef){
		$this->paymentRef = $paymentRef;
	}

	protected function getPaymentVar(){
		return $this->paymentVar;
	}

	protected function setPaymentVar($paymentVar){
		$this->paymentVar = $paymentVar;
	}

	public function getPaymentAcc(){
		return $this->paymentAcc;
	}

	protected function setPaymentAcc($paymentAcc){
		$this->paymentAcc = $paymentAcc;
	}

	public function getPaymentUser(){
		return $this->paymentUser;
	}

	protected function setPaymentUser($paymentUser){
		$this->paymentUser = $paymentUser;
	}

	public function getPaymentEmail(){
		return $this->paymentEmail;
	}

	protected function setPaymentEmail($paymentEmail){
		$this->paymentEmail = $paymentEmail;
	}

	public function getPaymentIp(){
		return $this->paymentIp;
	}

	protected function setPaymentIp($paymentIp){
		$this->paymentIp = $paymentIp;
	}

	public function getPaymentBy(){
		return $this->paymentBy;
	}

	protected function setPaymentBy($paymentBy){
		$this->paymentBy = $paymentBy;
	}

	public function getPaymentVal(){
		return $this->paymentVal;
	}

	protected function setPaymentVal($paymentVal){
		$this->paymentVal = $paymentVal;
	}

	public function setNewPayment(){
		$sql  = "INSERT INTO {$this->getServer()->loginDatabase}.".$this->getPaymentTable()." ";
		$sql .= "(account_id, userid, email,  payment_date, payment_id, payment, payment_ip, payment_type) ";
		$sql .= "VALUES (?, ?, ?,  NOW(), ?, ?, ?, ?)";
		$sth  = $this->getServer()->loginAthenaGroup->connection->getStatement($sql);
		$res  = $sth->execute(array(
			$this->getPaymentAcc(),
			$this->getPaymentUser(),
			$this->getPaymentEmail(),
			$this->getPaymentRef(),
			$this->getPaymentVal(),
			$this->getPaymentIp(),
			$this->getPaymentBy()
		));
	}

	public function getPayment(){
		$sql = "SELECT * FROM {$this->getServer()->loginDatabase}.".$this->getPaymentTable()." WHERE payment_id = ?";
		$sth = $this->getServer()->connection->getStatement($sql);
		$sth->execute(array($this->getPaymentRef()));
		return $sth->fetch();
	}
	
	public function setPayment(){
		$sql  = "UPDATE {$this->getServer()->loginDatabase}.".$this->getPaymentTable()." ";
		$sql .= "SET payment_code = ?, payment_status = ? ";
		$sql .= "WHERE payment_id = ?";
		$sth  = $this->getServer()->connection->getStatement($sql);
		$sth->execute(array($this->getTransactionCode(), $this->getPaymentStatus(), $this->getPaymentRef()));
	}

	public function setCashPointHercules(){
		$sql  = "INSERT INTO {$this->getServer()->charMapDatabase}.`acc_reg_num_db` (`account_id`, `key`, `index`, `value`) ";
		$sql .= "VALUES (?,?,0,?) ON DUPLICATE KEY UPDATE value = value + ?";
		$sth  = $this->getServer()->connection->getStatement($sql);
		$sth->execute(array($this->getPaymentAcc(), $this->getPaymentVar(), $this->getCredits(), $this->getCredits()));
	}

	public function setCashPointRathena(){
		$sql  = "INSERT INTO {$this->getServer()->charMapDatabase}.`acc_reg_num` (`account_id`, `key`, `index`, `value`) ";
		$sql .= "VALUES (?,?,0,?) ON DUPLICATE KEY UPDATE value = value + ?";
		$sth  = $this->getServer()->connection->getStatement($sql);
		$sth->execute(array($this->getPaymentAcc(), $this->getPaymentVar(), $this->getCredits(), $this->getCredits()));
	}

	public function setCashPointBrathena(){
		$sql  = "INSERT INTO {$this->getServer()->charMapDatabase}.`acc_reg_num_db` (`account_id`, `key`, `index`, `value`) ";
		$sql .= "VALUES (?,?,0,?) ON DUPLICATE KEY UPDATE value = value + ?";
		$sth  = $this->getServer()->connection->getStatement($sql);
		$sth->execute(array($this->getPaymentAcc(), $this->getPaymentVar(), $this->getCredits(), $this->getCredits()));
	}

	public function setCashPointEathena(){
		$sql  = "INSERT INTO {$this->getServer()->charMapDatabase}.`global_reg_value` (`char_id`, `str`, `value`, `type`, `account_id`) ";
		$sql .= "VALUES (0, ?, ?, 2, ?) ON DUPLICATE KEY UPDATE value = value + ?";
		$sth  = $this->getServer()->connection->getStatement($sql);
		$sth->execute(array($this->getPaymentVar(), $this->getCredits(), $this->getPaymentAcc(), $this->getCredits()));
	}

	public function setBan(){
		$sql  = "INSERT INTO {$this->getServer()->loginDatabase}.$this->getBanTable() (account_id, banned_by, ban_type, ban_until, ban_date, ban_reason) ";
		$sql .= "VALUES (?, ?, 2, '0000-00-00 00:00:00', NOW(), ?)";
		$sth  = $this->getServer()->connection->getStatement($sql);
		$sth->execute(array($this->getPaymentAcc(), 'PagSeguro', 'Tentativa de fraude ao sistema do PagSeguro: O doador abriu uma disputa/extornou o cartão após recebimento dos créditos e está banido.'));
					
		$sql  = "UPDATE {$this->getServer()->loginDatabase}.login SET state = 5, unban_time = 0 WHERE account_id = ?";
		$sth  = $this->getServer()->connection->getStatement($sql);
		$sth->execute(array($this->getPaymentAcc()));
	}
}