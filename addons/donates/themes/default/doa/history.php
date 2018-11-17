<?php if (!defined('FLUX_ROOT')) exit; ?>
<h2>Histórico do PagSeguro</h2>
<h3>Transações: Pendentes</h3>
<?php if ($pendentes): ?>
<p>Você possui <?php echo number_format($pendenteTotal);
echo ($pendenteTotal > 1)?(' transações pendentes'):(' transação pendente'); ?>.</p>
<table class="vertical-table">
	<tr>
		<th>Data da doação</th>
		<th>E-mail</th>
		<th>Tipo</th>
		<th>Valor</th>
	</tr>
	<?php foreach ($pendentes as $pendente): ?>
	<tr>
		<td><?php echo $this->formatDateTime($pendente->payment_date) ?></td>
		<td><?php echo htmlspecialchars($pendente->email) ?></td>
		<td><?php echo htmlspecialchars('Recebida por '.$pendente->payment_type) ?></td>
		<td>R$<?php echo number_format($pendente->payment) ?>,00</td>
	</tr>
	<tr>
		<td colspan="6">
			↳ Sua doação está em status de <?php $status = Flux::config('PagSeguroStatus')->toArray();
			echo htmlspecialchars($status[$pendente->pagseguro_status]) ?> no PagSeguro.
		</td>
	</tr>
	<?php endforeach ?>
</table>
<?php else: ?>
<p>Você não possui transações pendentes.</p>
<?php endif ?>

<h3>Transações: Aprovadas</h3>
<?php if ($aprovadas): ?>
<p>Você possui <?php echo number_format($aprovadaTotal);
echo ($aprovadaTotal > 1)?(' transações aprovadas'):(' transação aprovada'); ?>.</p>
<table class="vertical-table">
	<tr>
		<th>Data da doação</th>
		<th>E-mail</th>
		<th>Tipo</th>
		<th>Valor</th>
	</tr>
	<?php foreach ($aprovadas as $aprovada): ?>
	<tr>
		<td><?php echo $this->formatDateTime($aprovada->payment_date) ?></td>
		<td><?php echo htmlspecialchars($aprovada->email) ?></td>
		<td><?php echo htmlspecialchars('Recebida por '.$aprovada->payment_type) ?></td>
		<td>R$<?php echo number_format($aprovada->payment) ?>,00</td>
	</tr>
	<tr>
		<td colspan="6">
			↳ Sua doação está 
			<?php echo htmlspecialchars($status[$aprovada->pagseguro_status]) ?> no PagSeguro.
		</td>
	</tr>
	<?php endforeach ?>
</table>
<?php else: ?>
<p>Você não possui transações aprovadas.</p>
<?php endif ?>

<h3>Transações: Falhas</h3>
<?php if ($recusadas): ?>
<p>Você possui <?php echo number_format($recusadaTotal);
echo ($recusadaTotal > 1)?(' transações falhas'):(' transação falha'); ?>.</p>
<table class="vertical-table">
	<tr>
		<th>Data da doação</th>
		<th>E-mail</th>
		<th>Tipo</th>
		<th>Valor</th>
	</tr>
	<?php foreach ($recusadas as $recusada): ?>
	<tr>
		<td><?php echo $this->formatDateTime($recusada->payment_date) ?></td>
		<td><?php echo htmlspecialchars($recusada->email) ?></td>
		<td><?php echo htmlspecialchars('Recebida por '.$recusada->payment_type) ?></td>
		<td>R$<?php echo number_format($recusada->payment) ?>,00</td>
	</tr>
	<?php endforeach ?>
</table>
<?php else: ?>
<p>Você não possui transações falhas.</p>
<?php endif ?>