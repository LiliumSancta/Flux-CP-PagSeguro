<?php
/* 
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
 [1.2 Correções em erro de português e rate.             ]
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
 [                       Licença:                        ]
 [ This program is free software. It comes without any   ]
 [ warranty, to the extent permitted by applicable law.  ]
 [ You can redistribute it and/or modify it under the    ]
 [ terms of the Do What The Fuck You Want To Public      ]
 [ License, Version 2, as published by Sam Hocevar.      ]
 [ See http://www.wtfpl.net/ for more details.           ]
 [=======================================================]
 [                       Suporte:                        ]
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
	'modules' => array(
		'pagseguro' => array(
            'index' 	   => AccountLevel::NORMAL,
            'history'	   => AccountLevel::NORMAL,
			'return'       => AccountLevel::ANYONE,
			'process'      => AccountLevel::NORMAL,
		),
	),
	
	'modules' => array(
		'cplog' => array(
            'log' 				=> AccountLevel::ADMIN,
            'view' 				=> AccountLevel::ADMIN,
			'statistics' 		=> AccountLevel::ADMIN,
			'about' 			=> AccountLevel::ADMIN,
		),
	),
)
?>