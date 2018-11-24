<?php if (!defined('FLUX_ROOT')) exit; ?>
<h2>Visualizando Detalhes da Transação</h2>
<?php if (!empty($errorMessage)): ?>
		<p class="red"><?php echo htmlspecialchars($errorMessage) ?></p>
<?php endif ?>
<?php if ($payment): ?>
<table class="vertical-table">
	<tr>
		<th>Transação</th>
		<td><?php echo $payment->id ?></td>
		<th>Login da Conta</th>
		<td>
			<?php if ($auth->actionAllowed('account', 'view') && $auth->allowedToViewAccount): ?>
				<?php echo $this->linkToAccount($payment->account_id, $payment->userid) ?>
			<?php else: ?>
				<?php echo $payment->userid ?>
			<?php endif ?>
		</td>
	</tr>
	<tr>
		<th>ID da Conta</th>
		<td>
			<?php if ($auth->actionAllowed('account', 'view') && $auth->allowedToViewAccount): ?>
				<?php echo $this->linkToAccount($payment->account_id, $payment->account_id) ?>
			<?php else: ?>
				<?php echo $payment->account_id ?>
			<?php endif ?>
		</td>
		<th>Email da Conta</th>
		<td>
			<?php if ($auth->actionAllowed('account', 'view') && $auth->allowedToViewAccount): ?>
				<?php echo $this->linkToAccount($payment->account_id, $payment->email) ?>
			<?php else: ?>
				<?php echo htmlspecialchars($payment->email) ?>
			<?php endif ?>
		</td>
	</tr>
	<tr>
		<th>Data da Doação</th>
		<td><?php echo htmlspecialchars(date(Flux::config('DateTimeFormat'), strtotime($payment->payment_date))) ?></td>
		<th>Valor</th>
		<td>R$ <?php echo htmlspecialchars($this->formatCurrency($payment->payment)) ?></td>
	</tr>
	<tr>
		<th>Endereço Ip</th>
		<td><?php echo htmlspecialchars($payment->payment_ip) ?></td>
		
		<th>Tipo</th>
		<td><?php echo htmlspecialchars($payment->payment_type) ?></td>
	</tr>
	<tr>
		<th>ID da Transação</th>
		<td><?php echo htmlspecialchars($payment->payment_id) ?></td>
		<th>Status</th>
		<td>Status de 
			(<?php $status = Flux::config('PagSeguroStatus')->toArray();
			echo htmlspecialchars($status[$payment->payment_status]) ?>) no PagSeguro.
		</td>
	</tr>
<?php if ($payment->payment_code): ?>
	<tr>
		<th>Forma de Pagamento</th>
		<td><?php $type = Flux::config('PagSeguroType')->toArray();
		echo htmlspecialchars($type[$transactionPagseguro->getPaymentMethod()->getType()]) ?></td>
		
		<th>Nome Doador</th>
		<td><?php echo $transactionPagseguro->getSender()->getName() ?></td>
	</tr>
	<tr>
		<th>Telefone Doador</th>
		<td><?php echo '('.htmlspecialchars($transactionPagseguro->getSender()->getPhone()->getAreaCode().')'.$transactionPagseguro->getSender()->getPhone()->getNumber()) ?></td>
		<th>Email Doador</th>
		<td><?php echo htmlspecialchars($transactionPagseguro->getSender()->getEmail()) ?></td>
	</tr>
	<?php if ($payment->payment_status == 7 && !empty($transactionPagseguro->getCancelationSource())): ?>
		<tr>
			<th>Motivo do Cancelamento</th>
			<td colspan="3"><?php echo htmlspecialchars(($transactionPagseguro->getCancelationSource() == 'INTERNAL' ? 'O PagSeguro' : 'A instituição financeira') .' recusou a transação'); ?></td>
		</tr>
	<?php endif ?>
<?php endif ?>
</table>

<h2>Requisição de Status Manual</h2>

<form action="<?php echo $this->urlWithQs ?>" method="post">
		<table class="generic-form-table">
				<tr>
					<th><label for="numero">Código da Transação:</label></th>
					<td><input type="text" name="transaction_id" id="transaction_id" value="<?php echo htmlspecialchars($payment->payment_code) ?>" size=39/></td>
					<td>Coloque aqui o código da transação que quer solicitar o status manualmente.</td>
				</tr>				
				<tr>
					<td colspan=3><input type="submit" value="Enviar" /></td>
				</tr>
		</table>
</form>

<?php endif ?>