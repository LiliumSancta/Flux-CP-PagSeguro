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
 [      Integração API PagSeguro Versão atual 2.2        ]
 [=======================================================]
 [                      Changelog:                       ]
 [1.0 Addon Criado.                                      ]
 [1.2 Correção em erro de português e rate.              ]
 [1.4 Inseridas novas configurações.                     ]
 [1.6 Corrigido erro com valores.                        ]
 [1.8 Compatibilidade com Hercules adicionada.           ]
 [2.0 Addon totalmente reformulado, diversos erros       ]
 [    corrigidos e novas funções adicionadas.            ]
 [2.2 Atualizada API de pagamentos do PagSeguro para     ]
 [    ultima versão, inserido uso do PagSeguroLightbox,  ]
 [    código reformulado para uso de OO onde possível,   ]
 [    inserido sandbox do PagSeguro, remoção de arquivos ]
 [    desnecessários, inserido uso de ajax/jQuery/Json   ]
 [    se livrando das mudanças em lib/Flux/Template.php  ]
 [    e mais um monte de coisas que nem eu mesmo sei.    ]
 [-------------------------------------------------------]
 [     NÃO REDISTRIBUA MEU TRABALHO SEM AUTORIZAÇÃO      ]
 [=======================================================]
 [                       Suporte:                        ]
 [                                                       ]
 [ Qualquer erro encontrado pode ser reportado a mim em  ]
 [ meu email pessoal ou diretamente no tópico de         ]
 [ download.                                             ]
 [=======================================================]
 [                       Contato:                        ]
 [ Vitor J. Naidek (Lilium Sancta)                       ]
 [ inu-kai@hotmail.com                                   ]
 [ forum.brathena.org/index.php?/profile/17-fallenangel~ ]
 [ https://github.com/liliumsancta                       ]
 [=======================================================]
*/

return array(

	'EmailPagSeguro' 		=> 'seu-email@mail.com', // Seu E-mail do PagSeguro.

	'TokenPagseguro' 		=> 'SEU TOKEN PAGSEGURO', // Seu token do PagSeguro.

	'Promotion' 			=> 0, // Adicione aqui o bônus de porcentagem que deseja nas doações, exemplo caso insira 100 as doações receberão o dobro em Créditos.

	'InitPromo' 			=> 0, // Adicione aqui em R$ a partir de qual valor em doação o doador passa a receber o bônus das promoções.

	'rate' 					=> 0.001, // Adicione a rate das doações por exemplo 1.0 é equivalente a R$ 1.00 recebe 1 crédito, 0.001 a cada R$ 1.00 1000 Créditos (altere também a configuração 'CreditExchangeRate' no arquivo de configuração application.php do FluxCP para o mesmo valor).

	'emulator' 				=> 2, // Configure qual o seu emulador aqui (1 - Hercules / 2 - rAthena / 3 - brAthena / 4 - eAthena) em caso de duvidas teste todas opções caso nada de certo edite as queries em /lib/addon/Database.php.

	'PagSeguroMin' 			=> 5, // Doação minima, isto vai evitar doações de R$ 1.00 por exemplo que vão custar mais em taxas do que o valor recebido.

	'PagSeguroMax' 			=> 50, // Doação máxima, isto vai evitar doações de R$ 1000,00 usadas para quebra da economia do servidor, comprando vips e vendendo a preço de banana extornando o cartão depois.

	'PagSeguroFlux' 		=> false, // Usar sistema de créditos da loja do Flux CP? Caso insira false você vai precisar configurar uma variável abaixo.

	'PagSeguroVar' 			=> '#CASHPOINTS', // Caso a opção acima seja false adicione aqui a sua variável de cash (pode ser usada qualquer variável permanente de conta).

	'PagSeguroCoin' 		=> 'Cash Points', // Adicione aqui o nome da sua Moeda (ROPS, Cash Points, Kafra Points, SeuRO Points, Créditos ou seja o que for).

	'PagSeguroLock' 		=> false, // Trancar sistema de doações? Caso você esteja realizando testes configure como true e somente GMs com lvl acima de 20 terão acesso.

	'PagSeguroCurrency' 	=> 'BRL', // Moeda utilizada, é claro aqui é Brasil PORRA!

	'PagSeguroAddress' 		=> 'false', // Requisitar o endereço do comprador? (Padrão false) OBS: precisa ser passado como string '-' vai entender.

	'PagSeguroSandBox' 		=> false, // Usar o sandbox do Pagseguro ? (Padrão false). Acredite em mim integrei só por fazer isso nunca funcionou comigo, mas ta ai caso você precise/consiga usar...

	'PagSeguroSandBoxUrl' 	=> 'https://stc.sandbox.pagseguro.uol.com.br/pagseguro/api/v2/checkout/pagseguro.lightbox.js', // URL do SandBox do PagSeguro.

	'PagSeguroUrl' 			=> 'https://stc.pagseguro.uol.com.br/pagseguro/api/v2/checkout/pagseguro.lightbox.js', // URL para checkouts reais do PagSeguro.
	
	'MenuItems' => array(
		'DonationsLabel'   => array(
			'Doação PagSeguro'      => array('module' => 'pagseguro'),
		),
	),
	
	'SubMenuItems' => array(
		'pagseguro' => array(
			'index'   => 'Doar com PagSeguro',
			'history' => 'Histórico do PagSeguro',
		),
		'cplog' => array(
			'log'       => 'Transações PagSeguro',
			'statistics&dateMin=2000-01-01T01:00&dateMax=2050-01-01T01:00' => 'Estatísticas Pagseguro',
			'about'           => 'Sobre o Addon PagSeguro',
		),
	),

	//NÃO TOQUE EM NADA DAQUI PARA BAIXO
	'FluxTables' => array(
		'PaymentTable' => 'cp_donate',
	),
	'PagSeguroLib' => dirname(dirname(__FILE__)).'/lib/vendor/autoload.php',

	'PagSeguroStatus' => array (
		0 => 'Sem Continuidade',
		1 => 'Aguardando Pagamento',
		2 => 'Análise do Cartão',
		3 => 'Paga',
		4 => 'Finalizada',
		5 => 'Disputa',
		6 => 'Devolvida',
		7 => 'Cancelada',
		8 => 'Devolvida',
		9 => 'Extornada'
	),
	'PagSeguroType' => array (
		0 => 'Sem continuidade',
		1 => 'Cartão de crédito',
		2 => 'Boleto',
		3 => 'Débito online (TEF)',
		4 => 'Saldo PagSeguro',
		5 => 'Oi Paggo',
		6 => 'UNKNOW', // ????? Jamais iremos saber.
		7 => 'Depósito em conta'
	)
)
?>