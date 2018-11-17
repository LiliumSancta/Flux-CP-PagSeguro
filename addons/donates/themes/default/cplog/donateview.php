<?php if (!defined('FLUX_ROOT')) exit; ?>
<h2>Visualizando Detalhes da Transação</h2>
<?php if ($transaction): ?>
<table class="vertical-table">
	<tr>
		<th>Trasação</th>
		<td><?php echo htmlspecialchars($transaction->id) ?></td>
		<th>Login da Conta</th>
		<td>
			<?php if ($transaction->account_id): ?>
				<?php if ($auth->actionAllowed('account', 'view') && $auth->allowedToViewAccount): ?>
					<?php echo $this->linkToAccount($transaction->account_id, $transaction->userid) ?>
				<?php else: ?>
					<?php echo htmlspecialchars($transaction->userid) ?>
				<?php endif ?>
			<?php else: ?>
				<span class="not-applicable">Unknown</span>
			<?php endif ?>
		</td>
	</tr>
	<tr>
		
	</tr>
	<tr>
		<th>ID da Conta</th>
			<td>
			<?php if ($auth->actionAllowed('account', 'view') && $auth->allowedToViewAccount): ?>
				<?php echo $this->linkToAccount($transaction->account_id, $transaction->account_id) ?>
			<?php else: ?>
				<?php echo $transaction->account_id ?>
			<?php endif ?>
		</td>
		<th>Email da Conta</th>
		<td>
			<?php if ($auth->actionAllowed('account', 'index')): ?>
				<a href="<?php echo $this->url('cplog', 'donate', array('email' => $transaction->email)) ?>">
					<?php echo htmlspecialchars($transaction->email) ?>
				</a>
			<?php else: ?>
				<?php echo htmlspecialchars($transaction->email) ?>
			<?php endif ?>
		</td>
	</tr>
	<tr>
		<th>Data da Doação</th>
		<td><?php echo htmlspecialchars(date(Flux::config('DateTimeFormat'), strtotime($transaction->payment_date))) ?></td>
		<th>Valor</th>
		<td>R$<?php echo number_format((int)$transaction->payment) ?>,00</td>
	</tr>
	<tr>
		<th>Endereço Ip</th>
		<td><?php echo htmlspecialchars($transaction->payment_ip) ?></td>
		<th>Tipo</th>
		<td colspan="3"><?php echo htmlspecialchars($transaction->payment_type) ?></td>
	</tr>
	<tr>
		<th>ID da Transação</th>
		<td><?php echo htmlspecialchars($transaction->payment_id) ?></td>
		<th>Status</th>
		<td>Status de 
			<?php $status = Flux::config('PagSeguroStatus')->toArray(); 
			echo htmlspecialchars($status[$transaction->pagseguro_status]) ?> no PagSeguro.</td>
		</td>
	</tr>
<?php if ($transaction->pagseguro_code): ?>
	<tr>
		<th>Forma de Pagamento</th>
		<td><?php $type = Flux::config('PagSeguroType')->toArray();
		echo htmlspecialchars($type[$transactionpagseguro->getPaymentMethod()->getType()->getValue()]) ?></td>
		<th>Nome Doador</th>
		<td><?php echo $transactionpagseguro->getSender()->getName() ?></td>
	</tr>
	<tr>
		<th>Telefone Doador</th>
		<td><?php echo '('.htmlspecialchars($transactionpagseguro->getSender()->getPhone()->getAreaCode().')'.$transactionpagseguro->getSender()->getPhone()->getNumber()) ?></td>
		<th>Email Doador</th>
		<td><?php echo htmlspecialchars($transactionpagseguro->getSender()->getEmail()) ?></td>
	</tr>
<?php endif ?>
</table>
<?php endif ?>