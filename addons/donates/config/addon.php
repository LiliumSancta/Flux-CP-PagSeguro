<?php
/* 
 [=======================================================]
 [===========                                 ===========]
 [          =   +-+-+-+-+-+-+ +-+-+-+-+-+-+   =          ]
 [          =   |L|i|l|i|u|m| |S|a|n|c|t|a|   =          ]
 [          =   +-+-+-+-+-+-+ +-+-+-+-+-+-+   =          ]
 [          =         +-+-+-+-+-+-+-+         =          ]
 [          =         |S|c|r|i|p|t|s|         =          ]
 [          =         +-+-+-+-+-+-+-+         =          ]
 [===========                                 ===========]
 [=======================================================]
 [      Integração API PagSeguro Versão atual 1.4        ]
 [=======================================================]
 [                      Changelog:                       ]
 [1.0 Addon Criado.                                      ]
 [1.2 Correção em erro de português e rate.              ]
 [1.4 Inseridas novas configurações.                     ]
 [-------------------------------------------------------]
 [     NÃO REDISTRIBUA MEU TRABALHO SEM AUTORIZAÇÃO      ]
 [=======================================================]
 [                       Suporte:                        ]
 [                                                       ]
 [ Qualquer erro encontrado pode ser reportado a mim em  ]
 [ meu email pessoal inu-kai@limao.com.br ou diretamente ]
 [ no tópico referente no brAthena.                      ]
 [ OBS: Apenas usuários que tiverem adquirido o sistema  ]
 [ no brAthena receberão suporte.                        ]
 [=======================================================]
 [ http://www.brathena.org/forum/index.php?showuser=124  ]
 [=======================================================]
*/

return array(

	'EmailPagSeguro'  => 'email@email.com', // Seu E-mail do PagSeguro.
	'TokenPagseguro'  => 'SEU TOKEN PAGSEGURO', // Seu token do PagSeguro.
	'Promotion'      => 0, // Adicione aqui o bônus de porcentagem que deseja nas doações exemplo caso insira 100 as doações receberão o dobro em Créditos.
	'InitPromo'      => 0, // Adicione aqui a partir de qual valor em doação o doador passa a receber o bônus das promoções.
	'rate'           => 0.001, // Adicione a rate das doações por exemplo 1.0 é equivalente a 1.00 R$ recebe 1 crédito, 0.001 a cada 1.00 R$ 1000 Créditos.
	'PagSeguroMin'   => 5, //Doação minima.
	'PagSeguroFlux'  => false, // Usar sistema de créditos da loja do Flux CP? Caso insira false você vai precisar configurar uma variável abaixo.
	'PagSeguroVar'   => '#CASHPOINTS', // Caso a opção acima seja false adicione aqui a sua variável de cash (pode ser usada qualquer variável permanente de conta).
	'PagSeguroCoin'  => 'Cash Points', // Adicione aqui o nome da sua Moeda (ROPS, Cash Points, Kafra Points, SeuRO Points, Créditos ou seja o que for.)
	'PagSeguroLock'  => false, // Trancar sistema de doações? true ou false.
	
	//'MenuItems' => array(
		//'Donations'   => array(
			//'Doação PagSeguro'      => array('module' => 'doa'),
		//),
	//),
	
	'SubMenuItems' => array(
		'doa' => array(
			'index'   => 'Doar com PagSeguro',
			'history' => 'Histórico do PagSeguro',
		),
		'cplog' => array(
			'donatelog' => 'Log de Transações PagSeguro',
		),
	),

	//NÃO TOQUE EM NADA DAQUI PARA BAIXO
	'FluxTables' => array(
		'DonateTable' => 'cp_donate',
	),
	'PagSeguroLib' => dirname(dirname(__FILE__)).'/lib/PagSeguroLibrary.php',
	'PagSeguroStatus' => array (
		0 => 'sem continuidade',
		1 => 'aguardando pagamento',
		2 => 'análise do cartão',
		3 => 'paga',
		4 => 'terminada',
		5 => 'disputa',
		6 => 'devolvida',
		7 => 'cancelada'
	),
	'PagSeguroType' => array (
		0 => 'Não Concretizado',
		1 => 'Cartão de crédito',
		2 => 'Boleto',
		3 => 'Débito online (TEF)',
		4 => 'Saldo PagSeguro',
		5 => 'Oi Paggo',
		6 => 'UNKNOW',
		7 => 'Depósito em conta'
	),
	'PaymentStatus' => array (
		0 => 'Não Concretizado',
		1 => 'Pendente',
		2 => 'Aprovado',
		3 => 'Reprovado'
	)
)
?>