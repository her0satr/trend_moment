<?php
	$trend_moment = $this->trend_moment_model->calculate(array( 'book_id' => $_POST['book_id'] ));
	$count_data = $this->trend_moment_model->get_count(array( 'query' => 1, 'book_id' => $_POST['book_id'] ));
?>

<div class="widget-content">
	<table class="table table-striped table-bordered table-hover">
		<thead>
			<tr>
				<th class="center">Waktu</th>
				<th class="center">Penjualan</th>
			</tr>
		</thead>
		<tbody>
			<?php for ($i = 0; $i < 12; $i++) { ?>
			<?php $waktu = $i + $count_data; ?>
			<?php $y = $trend_moment['a'] + ($trend_moment['b'] * $waktu); ?>
			<tr>
				<td class="center"><?php echo $waktu; ?></td>
				<td class="center"><?php echo $y; ?></td>
			</tr>
			<?php } ?>
		</tbody>
	</table>
</div>