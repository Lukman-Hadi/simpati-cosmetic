<!-- Header -->
<div class="header bg-primary pb-6">
	<div class="container-fluid">
		<div class="header-body">
			<div class="row align-items-center py-4">
				<div class="col-lg-6 col-7">
					<h6 class="h2 text-white d-inline-block mb-0">Dashboard</h6>
					<h6 class="h2 text-white d-inline-block mb-0"></h6>
				</div>
			</div>
			<!-- Card stats -->
			<div class="row">
				<div class="col-xl-7 col-md-7">
					<div class="card card-stats pb-4">
						<!-- Card body -->
						<div class="card-body">
							<div class="row">
								<div class="col">
									<h5 class="card-title text-uppercase text-muted mb-0">Total Jenis Barang</h5>
									<span class="h2 font-weight-bold mb-0"> <?= $data['total_jenis_barang']['total'] ?> Barang </span>
								</div>
								<div class="col-auto">
									<div class="icon icon-shape bg-gradient-red text-white rounded-circle shadow">
										<i class="ni ni-active-40"></i>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-xl-5 col-md-5">
					<div class="card card-stats pb-4">
						<!-- Card body -->
						<div class="card-body">
							<div class="row">
								<div class="col">
									<h5 class="card-title text-uppercase text-muted mb-0">Total Stock</h5>
									<span class="h2 font-weight-bold mb-0"> <?= number_format($data['total_stock']['total']) ?> </span>
								</div>
								<div class="col-auto">
									<div class="icon icon-shape bg-gradient-orange text-white rounded-circle shadow">
										<i class="ni ni-chart-pie-35"></i>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Page content -->
<div class="container-fluid mt--6">
	<div class="row">
		<div class="col-6">
			<div class="card">
				<!-- Card header -->
				<div class="card-header border-0">
					<h3 class="mb-0">Barang hampir habis</h3>
				</div>
				<!-- Light table -->
				<div class="table-responsive">
					<table class="table align-items-center table-flush">
						<thead class="thead-light">
							<tr>
								<th scope="col">No</th>
								<th scope="col">Nama Barang</th>
								<th scope="col">Merk</th>
								<th scope="col">Sisa Stock</th>
							</tr>
						</thead>
						<tbody class="list">
							<?php foreach ($data['barang_hampir_habis'] as $key => $value) { ?>
								<tr>
									<td><?= $key + 1 ?></td>
									<td><?= $value['product_name'] ?></td>
									<td><?= $value['brand_name'] ?></td>
									<td><?= $value['total'] ?></td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
				<!-- Card footer -->
			</div>
		</div>
		<div class="col-6">
			<div class="card">
				<!-- Card header -->
				<div class="card-header border-0">
					<h3 class="mb-0">Barang hampir Kadaluarsa</h3>
				</div>
				<!-- Light table -->
				<div class="table-responsive">
					<table class="table align-items-center table-flush">
						<thead class="thead-light">
							<tr>
								<th scope="col">No</th>
								<th scope="col">Nama Barang</th>
								<th scope="col">Merk</th>
								<th scope="col">Total Stock</th>
							</tr>
						</thead>
						<tbody class="list">
							<?php foreach ($data['barang_hampir_habis'] as $key => $value) { ?>
								<tr>
									<td><?= $key + 1 ?></td>
									<td><?= $value['product_name'] ?></td>
									<td><?= $value['brand_name'] ?></td>
									<td><?= $value['total'] ?></td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
				<!-- Card footer -->
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-6">
			<div class="card">
				<!-- Card header -->
				<div class="card-header border-0">
					<h3 class="mb-0">Paling Banyak Terjual (Bulan Ini)</h3>
				</div>
				<!-- Light table -->
				<div class="table-responsive">
					<table class="table align-items-center table-flush">
						<thead class="thead-light">
							<tr>
								<th scope="col">No</th>
								<th scope="col">Nama Barang</th>
								<th scope="col">Merk</th>
								<th scope="col">Jumlah</th>
							</tr>
						</thead>
						<tbody class="list">
							<?php foreach ($data['paling_banyak_terjual'] as $key => $value) { ?>
								<tr>
									<td><?= $key + 1 ?></td>
									<td><?= $value['product_name'] ?></td>
									<td><?= $value['brand_name'] ?></td>
									<td><?= $value['total'] ?></td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
				<!-- Card footer -->
			</div>
		</div>
		<div class="col-6">
			<div class="card">
				<!-- Card header -->
				<div class="card-header border-0">
					<h3 class="mb-0">Paling Banyak Stok</h3>
				</div>
				<!-- Light table -->
				<div class="table-responsive">
					<table class="table align-items-center table-flush">
						<thead class="thead-light">
							<tr>
								<th scope="col">No</th>
								<th scope="col">Nama Barang</th>
								<th scope="col">Merk</th>
								<th scope="col">Total Stock</th>
							</tr>
						</thead>
						<tbody class="list">
							<?php foreach ($data['paling_banyak_stock'] as $key => $value) { ?>
								<tr>
									<td><?= $key + 1 ?></td>
									<td><?= $value['product_name'] ?></td>
									<td><?= $value['brand_name'] ?></td>
									<td><?= $value['total'] ?></td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
				<!-- Card footer -->
			</div>
		</div>
	</div>
</div>
</div>

<script>
	$(document).ready(function() {
		hideLoaderScreen();
	})
</script>
