<?php if (!defined('FLUX_ROOT')) exit; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<h2>Doação PagSeguro</h2>
<script type="text/javascript">
	$(function () {
		$('#formPagseguro').on('submit', function (e) {
			$('#buttonPagseguro').attr('disabled', true);
			$.ajax({
				type: 'POST',
				url: '<?php echo $this->url('pagseguro', 'process')?>',
				data: $(this).serialize(),
				dataType: 'json',
				success: function (data) {
					$('#buttonPagseguro').attr('disabled', false);
					if (data.error == true){
						$('.red').empty();
						$('.red').append(data.msg).show();
					}else{
						$('.red').append(data.msg).hide();
						if ("<?php echo Flux::config('PagSeguroLightBox'); ?>"){
							isOpenLightbox = PagSeguroLightbox(data.code,{
								success: function(transaction_id){
									// Isto aqui não foi muito legal =P.
									location.href="<?php echo $this->url($params->get('module'), 'return', array('transaction_id' => '')) ?>"+transaction_id;
								},
								abort: function(){
									$('.red').empty();
									$('.red').append('Doação Cancelada.').show();
								}
							});

							// Isto não parece ser mais necessário, porém vou manter já que não ouve nenhum pronunciamento do PagSeguro.
							if (!isOpenLightbox){
								if ("<?php echo Flux::config('PagSeguroEnviroment') == 'sandbox'; ?>")
									location.href="https://sandbox.pagseguro.uol.com.br/v2/checkout/payment.html?code="+data.code;
								else
									location.href="https://pagseguro.uol.com.br/v2/checkout/payment.html?code="+data.code;
							}
						}else {
							if ("<?php echo Flux::config('PagSeguroEnviroment') == 'sandbox'; ?>")
								location.href="https://sandbox.pagseguro.uol.com.br/v2/checkout/payment.html?code="+data.code;
							else
								location.href="https://pagseguro.uol.com.br/v2/checkout/payment.html?code="+data.code;
						}
					}
				}
			});
			e.preventDefault();
		});
	});
</script>
<?php if (!Flux::config('PagSeguroLock') || $session->account->group_id > 20): ?>

	<p class="red" style="display: none"></p>
	<p>Ao fazer uma doação, você está ajudando nos custos de <em>execução</em> deste servidor e na <em>manutenção</em> do mesmo. Em troca, você é recompensado com <span class="keyword"><?php echo Flux::config('PagSeguroCoin') ?></span> que você pode utilizar para comprar itens
	<?php echo (Flux::config('PagSeguroFlux') ? 'da nossa <a href="'. $this->url('purchase').'">loja</a> de' : 'em nosso NPC de') ?> <?php echo Flux::config('PagSeguroCoin') ?> .</p>
	<?php if (Flux::config('Promotion')):?>
		<h3><span class="keyword"> Aproveite estamos com uma promoção onde <?php echo ((Flux::config('InitPromo') > 0) ? 'a partir de R$ '.$this->formatCurrency(Flux::config('InitPromo')).' ' : ' ').'você recebe mais '. Flux::config('Promotion').'% de créditos nas doações.' ?></span></h3>
	<?php endif ?>
	<h3>Você está pronto para doar?</h3>
	<p>Aqui as doações são recebidas através do PagSeguro, onde você pode pagar de diversas maneiras.</p>
		
	<?php
	$paymentAmount     = (float)+Flux::config('rate');
	$creditAmount     = 1;
	$rateMultiplier   = 10;
	
	while ($paymentAmount < 1) {
		$paymentAmount  *= $rateMultiplier;
		$creditAmount  *= $rateMultiplier;
	}
	?>

	<div class="generic-form-div" style="margin-bottom: 10px">
		<table class="generic-form-table">
			<tr>
				<th><label>Taxa de Câmbio:</label></th>
				<td><p>R$ <?php echo $this->formatCurrency($paymentAmount) ?>
				= <?php echo number_format($creditAmount) ?> <?php echo Flux::config('PagSeguroCoin') ?>.</p></td>
			</tr>
			<tr>
				<th><label>Quantidade Mínima de Doação:</label></th>
				<td><p>R$ <?php echo $this->formatCurrency(Flux::config('PagSeguroMin')) ?></p></td>
			</tr>
		</table>
	</div>
		
	<form id="formPagseguro" method="POST">
		<input type="hidden" name="payment_type" value="PagSeguro">
		<p class="enter-donation-amount">
			<label>
				Digite a quantidade que você quer doar: R$
				<input class="money-input" type="text" name="amount"
					value="<?php echo htmlspecialchars($params->get('amount')) ?>"
					size="<?php echo (strlen((string)+Flux::config('rate')) * 2) + 2 ?>" />
			</label>
			ou
			<label>
				<input class="credit-input" type="text" name="credit-amount"
					value="<?php echo htmlspecialchars(intval($params->get('amount') / Flux::config('rate'))) ?>"
					size="<?php echo (strlen((string)+Flux::config('rate')) * 2) + 2 ?>" />
				<?php echo Flux::config('PagSeguroCoin') ?>
			</label>
		</p>
		<p style="text-align: center"><input type="image" name="submit" id="buttonPagseguro" src="https://p.simg.uol.com.br/out/pagseguro/i/botoes/doacoes/209x48-doar-assina.gif" alt="Pague com PagSeguro - é rápido, grátis e seguro!">
	</form>

<?php else: ?>
	<p><?php echo Flux::message('NotAcceptingDonations') ?></p>
<?php endif ?>