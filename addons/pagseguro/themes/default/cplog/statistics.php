<?php if (!defined('FLUX_ROOT')) exit; ?>
<h2>Estatísticas do PagSeguro</h2>

<?php if (!empty($errorMessage)): ?>
		<p class="red"><?php echo htmlspecialchars($errorMessage) ?></p>
<?php endif ?>

<form action="<?php echo $this->urlWithQs ?>" method="post">
		<table class="generic-form-table">
			<tr>
				<th><label for="dateMin">Inicial:</label></th>
				<td><input type="datetime-local" name="dateMin" id="dateMin" value="<?php echo htmlspecialchars('2014-01-01 00:00') ?>" /></td>
				<td>Data inicial para gerar as estatísticas.</td>
			</tr>
		
			<tr>
				<th align="left"><label for="dateMax">Final:</label></th>
				<td><input type="datetime-local" name="dateMax" id="dateMax" value="<?php echo htmlspecialchars(date('Y-m-d H:i')) ?>" /></td>
				<td>Data final para gerar as estatísticas.</td>
			</tr>
		
			<tr>
				<td colspan=3><input type="submit" value="Enviar" /></td>
			</tr>
		</table>
</form>
<?php if (!empty($transactionsTotal)): ?>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
	google.charts.load('visualization', '1', {'packages':['columnchart','corechart']});

	google.charts.setOnLoadCallback(createChartOne);
	function createChartOne() {
		var dataTable = new google.visualization.DataTable();
		dataTable.addColumn('string','Doações');
		dataTable.addColumn('number', 'Doações');
		dataTable.addRows([
			['Sem Continuidade',<?php echo $transactionsFail->total ?>],
			['Pendentes',<?php echo $transactionsWaiting->total ?>],
			['Aprovadas',<?php echo $transactionsApproved->total ?>],
			['Reprovadas',<?php echo $transactionsDisapproved->total ?>]
		]);
		var secondChart = new google.visualization.PieChart (document.getElementById('SecondChart'));
		var options = {
			height: 340,
			is3D: false,
			slices: {
	            0: { color: 'grey' },
	            1: { color: 'Orange' },
	            2: { color: 'MediumSeaGreen' },
	            3: { color: 'Tomato' }
          	}
		};
		secondChart.draw(dataTable, options);
	}

	google.charts.load("current", {packages:['corechart']});
    google.charts.setOnLoadCallback(createChartTwo);
    function createChartTwo() {
		var data = google.visualization.arrayToDataTable([
			['Doações', 'Quantidade', { role: 'style' }],
			['Sem Continuidade', <?php echo $transactionsFail->total ?>, 'color: grey'],
			['Pendentes', <?php echo $transactionsWaiting->total ?>, 'color: Orange'],
			['Aprovadas', <?php echo $transactionsApproved->total ?>, 'color: MediumSeaGreen'],
			['Reprovadas', <?php echo $transactionsDisapproved->total ?>, 'color: Tomato']
		]);
		var options = {
			title: "Doações",
			height: 240,
			is3D: false,
			legend: { position: 'none' },
      };
      var chart = new google.visualization.ColumnChart(document.getElementById('Chart'));
	      chart.draw(data, options);
	}
</script>

<br/>
<h2>Quantidade de Doações</h2>
<div id="Chart"></div>
<br/>
<table class="horizontal-table">
	<tr>
		<th>Sem continuidade</th>
		<th>Pendentes</th>
		<th>Aprovadas</th>
		<th>Reprovadas</th>
		<th>Valor Total Arrecadado</th>

	</tr>
	
	<tr align="center">
		<td>
			<a href="<?php echo $this->url($params->get('module'), 'log', array('dateMin' => $dateMin, 'dateMax' => $dateMax, 'payment_status' => '1')) ?>">
				<?php echo $transactionsFail->total ?>
			</a>
		</td>
		
		<td>
			<a href="<?php echo $this->url($params->get('module'), 'log', array('dateMin' => $dateMin, 'dateMax' => $dateMax, 'payment_status' => '2')) ?>">
				<?php echo $transactionsWaiting->total ?>
			</a>
		</td>
		
		<td>
			<a href="<?php echo $this->url($params->get('module'), 'log', array('dateMin' => $dateMin, 'dateMax' => $dateMax, 'payment_status' => '3')) ?>">
				<?php echo $transactionsApproved->total ?>
			</a>
		</td>
		
		<td>
			<a href="<?php echo $this->url($params->get('module'), 'log', array('dateMin' => $dateMin, 'dateMax' => $dateMax, 'payment_status' => '4')) ?>">
				<?php echo $transactionsDisapproved->total ?></td>
			</a>
		</td>
		
		<td>R$ <?php echo htmlspecialchars($this->formatCurrency($transactionValue->total)) ?></td>
	</tr>
</table>
<br/>
<h2>Porcentagem de Doações</h2>
<div id="SecondChart"></div>
<br/>
<table class="horizontal-table">
	<tr>
		<th>Sem Continuidade</th>
		<th>Pendentes</th>
		<th>Aprovadas</th>
		<th>Reprovadas</th>
		<th>Valor Total em Doações</th>

	</tr>
	
	<tr align="center">
		<td>
			<a href="<?php echo $this->url($params->get('module'), 'log', array('dateMin' => $dateMin, 'dateMax' => $dateMax, 'payment_status' => '1')) ?>">
				<?php echo $transactionsFail->total ? number_format((float)(($transactionsFail->total / $transactionsTotal->total) * 100),1, '.','') : 0; ?>%
			</a>
		</td>
		
		<td>
			<a href="<?php echo $this->url($params->get('module'), 'log', array('dateMin' => $dateMin, 'dateMax' => $dateMax, 'payment_status' => '2')) ?>">
				<?php echo $transactionsWaiting->total ? number_format((float)(($transactionsWaiting->total / $transactionsTotal->total) * 100),1, '.','') : 0; ?>%
			</a>
		</td>
		
		<td>
			<a href="<?php echo $this->url($params->get('module'), 'log', array('dateMin' => $dateMin, 'dateMax' => $dateMax, 'payment_status' => '3')) ?>">
				<?php echo $transactionsApproved->total ? number_format((float)(($transactionsApproved->total / $transactionsTotal->total) * 100),1, '.',''): 0; ?>%
			</a>
		</td>
	
		<td>
			<a href="<?php echo $this->url($params->get('module'), 'log', array('dateMin' => $dateMin, 'dateMax' => $dateMax, 'payment_status' => '4')) ?>">
				<?php echo $transactionsDisapproved->total ? number_format((float)(($transactionsDisapproved->total / $transactionsTotal->total) * 100),1, '.','') : 0; ?>%</td>
			</a>
		</td>
		
		<td>R$ <?php echo htmlspecialchars($this->formatCurrency($transactionValueFail->total)) ?></td>
	</tr>
</table>

<?php endif ?>
