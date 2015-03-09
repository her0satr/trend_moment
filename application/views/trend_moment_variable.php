<?php
	$trend_moment = $this->trend_moment_model->calculate(array( 'book_id' => $_POST['book_id'] ));
?>

<div>
	Data Variabel<br />
	<ul>
		<li>Jumlah Penjualan : <?php echo $trend_moment['summary_penjualan']; ?></li>
		<li>Jumlah Waktu : <?php echo $trend_moment['summary_waktu']; ?></li>
		<li>Jumlah xy : <?php echo $trend_moment['summary_xy']; ?></li>
		<li>Jumlah x<sup>2</sup> : <?php echo $trend_moment['summary_x2']; ?></li>
		<li>Rata-Rata Penjualan : <?php echo $trend_moment['average_penjualan']; ?></li>
		<li>Rata-Rata Waktu : <?php echo $trend_moment['average_waktu']; ?></li>
		<li>a : <?php echo $trend_moment['a']; ?></li>
		<li>b : <?php echo $trend_moment['b']; ?></li>
	</ul>
</div>
