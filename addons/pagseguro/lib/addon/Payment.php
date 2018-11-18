<?php

namespace PagSeguro\addon;

use \PagSeguro\Addon\Database;

class Payment extends Database{

	protected $paymentPromo;
	protected $paymentFlux;
	protected $emulator;
	protected $initPromo;
	protected $rate;
	protected $credits;

	public function setNewUpdate(
		$paymentAcc,
		$paymentVal,
		$transactionCode,
		$paymentStatus,
		$paymentVar,
		$initPromo,
		$paymentPromo,
		$paymentFlux,
		$emulator,
		$rate
	){
		$this->setPaymentAcc($paymentAcc);
		$this->setPaymentVal($paymentVal);
		$this->setTransactionCode($transactionCode);
		$this->setPaymentStatus($paymentStatus);
		$this->setPaymentVar($paymentVar);
		$this->setInitPromo($initPromo);
		$this->setPaymentPromo($paymentPromo);
		$this->setPaymentFlux($paymentFlux);
		$this->setEmulator($emulator);
		$this->setRate($rate);
		$this->setCredits();
	}

	public function getPaymentPromo(){
		return $this->paymentPromo;
	}

	protected function setPaymentPromo($paymentPromo){
		$this->paymentPromo = $paymentPromo;
	}

	public function getPaymentFlux(){
		return $this->paymentFlux;
	}

	protected function setPaymentFlux($paymentFlux){
		$this->paymentFlux = $paymentFlux;
	}

	public function getEmulator(){
		return $this->emulator;
	}

	protected function setEmulator($emulator){
		$this->emulator = $emulator;
	}

	public function getInitPromo(){
		return $this->initPromo;
	}

	protected function setInitPromo($initPromo){
		$this->initPromo = $initPromo;
	}

	public function getRate(){
		return $this->rate;
	}

	public function setRate($rate){
		$this->rate = $rate;
	}

	public function getCredits(){
		return $this->credits;
	}

	protected function setCredits(){
		$this->credits = floor(($this->getPaymentVal() >= $this->getInitPromo() ? ($this->getPaymentVal() + ($this->getPaymentPromo()*$this->getPaymentVal())/100) : $this->getPaymentVal()) / $this->getRate());
	}

	public function setPaymentUpdate(){

		$this->setPayment();
		
		switch ($this->getPaymentStatus()){
			case 3:
				if ($this->getPaymentFlux()){
					$this->getServer()->loginServer->depositCredits($this->getPaymentAcc(), $this->getCredits(), $this->getPaymentVal());
				} elseif ($this->getEmulator() == 1){
					$this->setCashPointHercules();
				} elseif ($this->getEmulator() == 2){
					$this->setCashPointRathena();
				} elseif ($this->getEmulator() == 3){
					$this->setCashPointBrathena();
				} elseif ($this->getEmulator() == 4){
					$this->setCashPointEathena();
				}

			break;
				
			case 5:
				$this->setBan();
			break;

			case 6:
				$this->setBan();
			break;

			case 8:
				$this->setBan();
			break;

			case 9:
				$this->setBan();
			break;
		}
	}
}