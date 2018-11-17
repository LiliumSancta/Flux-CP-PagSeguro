<?php if (!defined('FLUX_ROOT')) exit;
$this->loginRequired();
header('Refresh: 10; URL='.$this->url('doa', 'history', array('_host' => true)).'');
?>
<h2>Obrigado por sua doação</h2>
<p>Agradecemos por sua <?php echo $session->account->userid;?>, ela é muito importante
para nós e será utilizada para melhoria de nossos servidores.
Assim que aprovada pelo sistema do PagSeguro ela será automaticamente adicionada em sua conta.</p>
<p>Você está sendo redirecionado para a página Histórico do PagSeguro para visualizar o status de sua doação.</p>