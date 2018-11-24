<?php if (!defined('FLUX_ROOT')) exit;
$this->loginRequired(Flux::message('LoginToDonate'));

// Composer é vida =)
require_once Flux::config('PagSeguroLib');

\PagSeguro\Library::initialize();
\PagSeguro\Library::cmsVersion()->setName("Nome")->setRelease("1.0.0");
\PagSeguro\Library::moduleVersion()->setName("Nome")->setRelease("1.0.0");

use \PagSeguro\Addon\Database as DB;

if (\PagSeguro\Helpers\Xhr::hasPost()) {

	$paymentTable = Flux::config('FluxTables.PaymentTable');
	$rate         = Flux::config('rate');
	$paymentPromo = Flux::config('Promotion');
	$initPromo    = Flux::config('InitPromo');
	$paymentFlux  = Flux::config('PagSeguroFlux');
	$paymentCoin  = Flux::config('PagSeguroCoin');
	$paymentMin   = Flux::config('PagSeguroMin');
	$paymentMax   = Flux::config('PagSeguroMax');

	$paymentVal   = (float)$params->get('amount');
	$paymentEmail = $session->account->email;
	$paymentAcc   = $session->account->account_id;
	$paymentInf   = sprintf('%s '.strtoupper($paymentCoin)  , number_format(floor(($paymentVal >= $initPromo ? ($paymentVal + ($paymentPromo*$paymentVal)/100) : $paymentVal) / $rate)));
	$paymentIp    = $_SERVER['REMOTE_ADDR'];
	$paymentRef   = strtoupper(uniqid(true));
	$paymentUser  = $session->account->userid;
	$paymentBy    = $params->get('payment_type');

	// Prefiro trabalhar com Objetos que Arrays, logo stdClass.
	$returnJson = new stdClass();
	$returnJson->error = false;

	if ($paymentBy != 'PagSeguro') {

		// Para um futuro próximo, caso eu queira (ME FODER) tentar implementar outro sistema de pagamento no mesmo addon.
		$returnJson->msg = 'Somente PagSeguro';
		$returnJson->error = true;
	}
	else if($paymentVal < $paymentMin) {

		// Adicionando ao Json erro devido ao baixo valor da transação.
		$returnJson->msg = sprintf('O valor da doação deve ser maior ou igual a R$ %s!', $this->formatCurrency($paymentMin));
		$returnJson->error = true;
	}
	else if($paymentVal > $paymentMax){

		// Adicionando ao Json erro devido ao alto valor da transação.
		$returnJson->msg = sprintf('O valor da doação deve ser menor ou igual a R$ %s!', $this->formatCurrency($paymentMax));
		$returnJson->error = true;
	} else {

		// Isto é errado mas foi um mau necessário, não quero criar outra conexão com o banco de dados e não quero mecher no código nativo do Flux CP. Se alguem tiver uma ideia melhor me avise.
		$database = new DB($server);

		// Configurações, não quero setar em um xml altamente inseguro.
		$config = new \PagSeguro\Configuration\Configure();

		// Instanciando um novo pagamento.
		$payment = new \PagSeguro\Domains\Requests\Payment();
	
		// Setando moeda corrente.
		$payment->setCurrency(Flux::config('PagSeguroCurrency'));

		// Pedir endereço?
		$payment->setShipping()->setAddressRequired()->withParameters(Flux::config('PagSeguroAddress'));

		//Adicionando produto (O setor de análise de risco do PagSeguro está muito chato, por isso estou enviando dados randomicos para eles se divertirem).
		$payment->addItems()->withParameters(
			rand(1, 100),
			$paymentInf,
			'1',
			str_replace(",","",$this->formatCurrency($paymentVal))
		);

		// Setando identificador unico para o produto.
		$payment->setReference($paymentRef);

		// Setando URL de notificações.
		$payment->setNotificationURL($this->url('pagseguro', 'return', array('_host' => true)));

		// Setando URL de retorno.
		$payment->setRedirectURL($this->url('pagseguro', 'return', array('_host' => true)));

		// Não me pergunte! Pergunte ao pagseguro o porque no exemplo da api deles eles enviam isto DUAS vezes através do método setNotificationURL e este aqui.
		$payment->addParameter()->withArray(['notificationURL', $this->url('pagseguro', 'return', array('_host' => true))]);

		// Não me pergunte! Pergunte ao pagseguro o porque no exemplo da api deles eles enviam isto DUAS vezes através do método setRedirectURL e este aqui.
		$payment->addParameter()->withArray(['redirectURL', $this->url('pagseguro', 'return', array('_host' => true))]);

		// Vamos lidar com isto com try catch e não deixar para o flux cp já que isto será usado numa chamada ajax. Logo é problema nosso.
		try {

			// Setando enviroment do pagseguro.
			$config->setEnvironment(Flux::config('PagSeguroEnviroment'));

			// Setando credenciais do pagseguro.
			$config->setAccountCredentials(Flux::config('EmailPagSeguro'), Flux::config('PagSeguroEnviroment') == 'sandbox' ? Flux::config('TokenPagseguroSandbox') : Flux::config('TokenPagseguro'));

			// Registrando transação com as credenciais.
			$transaction = $payment->register($config->getAccountCredentials(), true);

			// Preparando classe Database com parametros necessários para inserir transação no banco de dados.
			$database->setNewPaymentParams($paymentAcc, $paymentUser, $paymentEmail, $paymentRef, $paymentIp, $paymentBy, $paymentVal, $paymentTable);

			// Adicionado transação ao banco de dados.
			$database->setNewPayment();

			// Adicionando ao Json código da transação.
			$returnJson->code = $transaction->getCode();

        } catch (Exception $e) {

        	// Adicionando ao Json erro gerado dentro da api do pagseguro.
        	$returnJson->error = true;
	    	$returnJson->msg = $e->getMessage();
		}
	}
	
	//Retornando Json a página de doações.
	echo json_encode($returnJson);

	// Morra FLUX CP!
	die;
}
	// Se você leu todos os comentários desse código e a documentação desta coisa você tem problemas amigo, se sou eu mesmo lendo anos depois... Boa sorte cara vai precisar.
?>