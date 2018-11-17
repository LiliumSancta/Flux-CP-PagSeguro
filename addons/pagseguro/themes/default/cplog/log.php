<?php if (!defined('FLUX_ROOT')) exit; ?>
<h2>Transações do PagSeguro</h2>
<p class="toggler"><a href="javascript:toggleSearchForm()">Procurar...</a></p>
<form action="<?php echo $this->url ?>" method="get" class="search-form">
	<?php echo $this->moduleActionFormInputs($params->get('module'), $params->get('action')) ?>
		<p>
		<label for="id">ID da Transação:</label>
		<input type="text" name="id" id="id" value="<?php echo htmlspecialchars($params->get('id')) ?>" />
		</p>
		
		<p>
		<label for="payment_code">Código da Transação:</label>
		<input type="text" name="payment_code" id="payment_code" value="<?php echo htmlspecialchars($params->get('payment_code')) ?>" />
		</p>
		
		<p>
		<label for="payment_id">Referência da Transação:</label>
		<input type="text" name="payment_id" id="payment_id" value="<?php echo htmlspecialchars($params->get('payment_id')) ?>" />
		</p>

		<p>
		<label for="account_id">ID da Conta:</label>
		<input type="text" name="account_id" id="account_id" value="<?php echo htmlspecialchars($params->get('account_id')) ?>" />
		</p>

		<p>
		<label for="userid">Usuário:</label>
		<input type="text" name="userid" id="userid" value="<?php echo htmlspecialchars($params->get('userid')) ?>" />
		</p>

		<p>
		<label for="ip">Endereço de IP:</label>
		<input type="text" name="payment_ip" id="payment_ip" value="<?php echo htmlspecialchars($params->get('payment_ip')) ?>" />
		</p>

		<p>
		<label for="payment">Valor: R$</label>
		<input type="text" name="payment" id="payment" value="<?php echo htmlspecialchars($params->get('payment')) ?>" />
		</p>

		<p>
		<label for="email">Email:</label>
		<input type="text" name="email" id="email" value="<?php echo htmlspecialchars($params->get('email')) ?>" />
		</p>

		<p>
		<label for="payment_status_log">Status:</label>
		<select name="payment_status_log" id="payment_status_log" value="<?php echo htmlspecialchars($params->get('payment_status_log')) ?>">
			<option value=""></option>
			<?php $status = Flux::config('PagSeguroStatus')->toArray();
				foreach ($status as $key => $value) {
					echo '<option value="'.$key.'">'.$value.'</option>';
				}
			?>
		</select>
		</p>

		<input type="submit" value="Procurar" />
		<input type="button" value="Resetar" onclick="reload()" />
</form>

<?php echo $paginator->infoText() ?>
<table class="horizontal-table">
	<tr>
		<th><?php echo $paginator->sortableColumn('id', 'ID') ?></th>
		<th><?php echo $paginator->sortableColumn('payment_id', 'Referência') ?></th>
		<th><?php echo $paginator->sortableColumn('userid', 'Usuário') ?></th>
		<th><?php echo $paginator->sortableColumn('payment_status', 'Status') ?></th>
		<th><?php echo $paginator->sortableColumn('payment_date', 'Data do envio') ?></th>
		<th><?php echo $paginator->sortableColumn('payment', 'Valor') ?></th>
	</tr>
	<?php foreach ($transactions as $transaction): ?>
	<tr>
		<td align="right">
				<a href="<?php echo $this->url($params->get('module'), 'view', array('payment_id' => $transaction->payment_id)) ?>">
					<?php echo $transaction->id ?>
				</a>
		</td>
		
		<td align="right">
				<a href="<?php echo $this->url($params->get('module'), 'view', array('payment_id' => $transaction->payment_id)) ?>">
				<?php echo $transaction->payment_id ?>
				</a>
		</td>
		<td><?php echo htmlspecialchars($transaction->userid) ?></td>
	
		<td><?php echo htmlspecialchars($status[$transaction->payment_status]) ?>.</td>

		<td><?php echo $this->formatDateTime($transaction->payment_date) ?></td>
		<td>R$ <?php echo htmlspecialchars($this->formatCurrency($transaction->payment)) ?></td>
	</tr>
	<?php endforeach ?>
</table>
<?php echo $paginator->getHTML() ?>
