<?php if (!defined('FLUX_ROOT')) exit;

// Composer é vida =)
require_once Flux::config('PagSeguroLib');

\PagSeguro\Library::initialize();
\PagSeguro\Library::cmsVersion()->setName("Nome")->setRelease("1.0.0");
\PagSeguro\Library::moduleVersion()->setName("Nome")->setRelease("1.0.0");

use \PagSeguro\Addon\Payment as DB;

// Queria unificar as páginas de retorno e notificações reduzindo ao maximo código repetido e deu nisso ai =P.
if (\PagSeguro\Helpers\Xhr::hasGet() || \PagSeguro\Helpers\Xhr::hasPost()){

	$paymentTable = Flux::config('FluxTables.PaymentTable');
	$banTable     = Flux::config('FluxTables.AccountBanTable');
	$paymentPromo = Flux::config('Promotion');
	$initPromo    = Flux::config('InitPromo');
	$paymentVar   = Flux::config('PagSeguroVar');
	$paymentFlux  = Flux::config('PagSeguroFlux');
	$emulator     = Flux::config('emulator');
	$rate         = Flux::config('rate');

	// Configurações, não quero setar em um xml altamente inseguro.
	$config  = new \PagSeguro\Configuration\Configure();

	// Setando enviroment do pagseguro.
	$config->setEnvironment(Flux::config('PagSeguroEnviroment'));

	// Setando credenciais do pagseguro.
   	$config->setAccountCredentials(Flux::config('EmailPagSeguro'), Flux::config('PagSeguroEnviroment') == 'sandbox' ? Flux::config('TokenPagseguroSandbox') : Flux::config('TokenPagseguro'));

   	// Queria unificar as páginas de retorno e notificações reduzindo ao maximo código repetido e deu nisso ai =P.
	if (!empty($_GET['transaction_id'])){

		// Em caso de consulta ao retorno de uma transação é necessário estar logado.
		$this->loginRequired(Flux::message('LoginToDonate'));

   		// Transação originária do sistema de retorno.
		$transaction = \PagSeguro\Services\Transactions\Search\Code::search($config->getAccountCredentials(), $_GET['transaction_id']);
	} else {
		
		// Transação originária do sistema de notificações.
		$transaction = \PagSeguro\Services\Transactions\Notification::check($config->getAccountCredentials());
	}
	
	// Isto é errado mas foi um mau necessário, não quero criar outra conexão com o banco de dados e não quero mecher no código nativo do Flux CP. Se alguem tiver uma ideia melhor me avise.
	$database = new DB($server);

	// Pegando status da transação.
	$paymentStatus = $transaction->getStatus();

	// Pegando referência da transação.
	$paymentRef    = $transaction->getReference();

	// Pegando código da transação.
	$paymentCode   = $transaction->getCode();

	// Preparando classe com parametros necessários para consultar transação no banco de dados (banTable não era necessário vir aqui, mas e dai?).
	$database->setUpdateParams($paymentTable, $banTable, $paymentRef);

	// Pegando dados da transação armazendos no banco de dados.
	$payment = $database->getPayment();

	// Status no banco de dados igual ao recebido do PagSeguro, então morra Flux CP!
	if ($paymentStatus != $payment->payment_status){

		// Preparando atualização da transação no banco de dados com os dados recebido do PagSeguro.
		$database->setNewUpdate(
			$payment->account_id,
			$payment->payment,
			$paymentCode,
			$paymentStatus,
			$paymentVar,
			$initPromo,
			$paymentPromo,
			$paymentFlux,
			$emulator,
			$rate
		);

		// Atualizando transação no banco de dados.
		$database->setPaymentUpdate();

	} 
}
	// Se você leu todos os comentários desse código e a documentação desta coisa você tem problemas amigo, se sou eu mesmo lendo anos depois... Boa sorte cara vai precisar.
?>