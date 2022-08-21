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
		<h1>Daftar Barang</h1>
	</div>
</div>
<table class="table print" style="width: 100%;">
	<thead>
		<th style="width: 15%;">Nama Barang</th>
		<th style="width: 10%;">Kode Barang</th>
		<th style="width: 10%;">Merk Barang</th>
		<th style="width: 15%;">Daftar Variant</th>
		<th style="width: 5%;">Harga</th>
		<th style="width: 10%;">Harga Apotik</th>
	</thead>
	<tbody>
		<?php foreach ($data as $key => $value) { ?>
			<tr>
				<td><?= $value['product_name'] ?></td>
				<td><?= $value['product_code'] ?></td>
				<td><?= $value['brand_name'] ?></td>
				<td><?= $value['list_variant'] == $value['product_name'] ? '-' : $value['list_variant'] ?></td>
				<td><?= 'Rp. ' . number_format($value['sell_value']) ?></td>
				<td><?= 'Rp. ' . number_format($value['price_dist']) ?></td>
			</tr>
		<?php } ?>
	</tbody>
</table>
