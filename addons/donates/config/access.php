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
	'modules' => array(
		'doa' => array(
            'index' 	   => AccountLevel::NORMAL,
            'history'	   => AccountLevel::NORMAL,
			'return'       => AccountLevel::NORMAL,
			'notification' => AccountLevel::ANYONE,
			'process'      => AccountLevel::NORMAL,
		),
	),
	
	'modules' => array(
		'cplog' => array(
            'donatelog' 	=> AccountLevel::ADMIN,
            'donateview' 	=> AccountLevel::ADMIN,
		),
	),
)
?>