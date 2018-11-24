<?php if (!defined('FLUX_ROOT')) exit;
$this->loginRequired(Flux::message('LoginToDonate'));
$title = 'Visualizando Transação';

// Composer é vida =)
require_once Flux::config('PagSeguroLib');

\PagSeguro\Library::initialize();
\PagSeguro\Library::cmsVersion()->setName("Nome")->setRelease("1.0.0");
\PagSeguro\Library::moduleVersion()->setName("Nome")->setRelease("1.0.0");

use \PagSeguro\Addon\Payment as DB;

$paymentTable = Flux::config('FluxTables.PaymentTable');
$banTable     = Flux::config('FluxTables.AccountBanTable');
$paymentPromo = Flux::config('Promotion');
$initPromo    = Flux::config('InitPromo');
$paymentVar   = Flux::config('PagSeguroVar');
$paymentFlux  = Flux::config('PagSeguroFlux');
$rate         = Flux::config('rate');
$emulator     = Flux::config('emulator');
$paymentRef   = trim($params->get('payment_id'));

// Isto é errado, mas foi um mau necessário não quero criar outra conexão com o banco de dados e não quero mecher no código nativo do Flux CP. Se alguem tiver uma ideia melhor me avise.
$database = new DB($server);

// Preparando classe com parametros necessários para consultar transação no banco de dados (banTable não era necessário vir aqui, mas e dai?).
$database->setUpdateParams($paymentTable, $banTable, $paymentRef);

// Pegando dados da transação armazendos no banco de dados.
$payment = $database->getPayment();

// Configurações, não quero setar em um xml altamente inseguro.
$config = new \PagSeguro\Configuration\Configure();

// Setando enviroment do pagseguro.
$config->setEnvironment(Flux::config('PagSeguroEnviroment'));

// Setando credenciais do pagseguro.
$config->setAccountCredentials(Flux::config('EmailPagSeguro'), Flux::config('PagSeguroEnviroment') == 'sandbox' ? Flux::config('TokenPagseguroSandbox') : Flux::config('TokenPagseguro'));

if ($payment->payment_code){
	
	// Pegando dados da transação através do código de transação armazenado na tabela.
	$transactionPagseguro = \PagSeguro\Services\Transactions\Search\Code::search($config->getAccountCredentials(), $payment->payment_code);
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){
	if (count($_POST) && !empty($_POST['transaction_id'])){

		// Pegando dados da transação através do código de transação recebido no POST.
		$transactionPagseguro = \PagSeguro\Services\Transactions\Search\Code::search($config->getAccountCredentials(), $_POST['transaction_id']);

		// Status no banco de dados igual ao recebido do PagSeguro, então GG paramos por aqui!
		if ($transactionPagseguro->getStatus() != $payment->payment_status){

			// Preparando atualização da transação no banco de dados com os dados recebido do PagSeguro.
			$database->setNewUpdate(
				$payment->account_id,
				$payment->payment,
				$_POST['transaction_id'],
				$transactionPagseguro->getStatus(),
				$paymentVar,
				$initPromo,
				$paymentPromo,
				$paymentFlux,
				$emulator,
				$rate
			);

			// Atualizando transação no banco de dados.
			$database->setPaymentUpdate();

			// Pegando novamente os dados da transação armazendos no banco de dados para atualizar a view.
			$payment = $database->getPayment();
		}
	}
}

?>