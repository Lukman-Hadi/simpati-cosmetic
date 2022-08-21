<style>
	.table thead th {
		font-weight: bold;
		white-space: nowrap;
		text-decoration-color: black !important;
	}

	table tr th,
	table tr td {
		border-color: black !important;
		border: 0.5px solid;
	}

	.table thead th,
	.table tr td {
		word-wrap: break-word !important;
		white-space: normal !important;
		vertical-align: middle !important;
		text-align: center;
		color: black !important;
	}

	.table tr td {
		padding: 0;
	}
</style>
<div class="row">
	<div class="col-6">
		<h1>Laporan Keluar Masuk Barang</h1>
	</div>
	<div class="col-6">
		<h1 class="text-right"><?= date('d F Y', strtotime($date['startDate'])) . ' S/D ' . date('d F Y', strtotime($date['endDate'])) ?></h1>
	</div>
</div>
<table class="table print" style="width: 100%;">
	<thead>
		<th style="width: 15%;">Nama Barang</th>
		<th style="width: 10%;">Kode Barang</th>
		<th style="width: 10%;">Merk Barang</th>
		<th style="width: 15%;">Nama Variant</th>
		<!-- <th style="width: 10%;">Kode Variant</th> -->
		<th style="width: 5%;">Jumlah Barang Keluar (Qty)</th>
		<th style="width: 10%;">Jumlah Barang Keluar (Rp)</th>
		<th style="width: 5%;">Jumlah Barang Masuk (Qty)</th>
		<th style="width: 10%;">Jumlah Barang Masuk (Rp)</th>
	</thead>
	<tbody>
		<?php foreach ($data as $key => $value) { ?>
			<tr>
				<td rowspan="<?= count($value['variant']) ?>"><?= $value['product_name'] ?></td>
				<td rowspan="<?= count($value['variant']) ?>"><?= $value['product_code'] ?></td>
				<td rowspan="<?= count($value['variant']) ?>"><?= $value['brand_name'] ?></td>
				<td><?= $value['variant'][0]['variant_name'] ?></td>
				<td><?= $value['variant'][0]['qty_out'] ? $value['variant'][0]['qty_out'] : '-' ?></td>
				<td><?= $value['variant'][0]['total_amount_out'] ? 'Rp. ' . number_format($value['variant'][0]['total_amount_out']) : '-' ?></td>
				<td><?= $value['variant'][0]['qty_in'] ? $value['variant'][0]['qty_in'] : '-' ?></td>
				<td><?= $value['variant'][0]['total_amount_in'] ? 'Rp. ' . number_format($value['variant'][0]['total_amount_in']) : '-' ?></td>
			</tr>
			<?php for ($i = 1; $i < count($value['variant']); $i++) { ?>
				<tr>
					<td><?= $value['variant'][$i]['variant_name'] ?></td>
					<td><?= $value['variant'][$i]['qty_out'] ? $value['variant'][$i]['qty_out'] : '-' ?></td>
					<td><?= $value['variant'][$i]['total_amount_out'] ? 'Rp. ' . number_format($value['variant'][$i]['total_amount_out']) : '-' ?></td>
					<td><?= $value['variant'][$i]['qty_in'] ? $value['variant'][$i]['qty_in'] : '-' ?></td>
					<td><?= $value['variant'][$i]['total_amount_in'] ? 'Rp. ' . number_format($value['variant'][$i]['total_amount_in']) : '-' ?></td>
				</tr>
			<?php } ?>
			<tr>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
		<?php } ?>
	</tbody>
</table>
