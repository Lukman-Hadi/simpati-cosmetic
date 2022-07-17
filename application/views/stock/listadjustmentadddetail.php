<style>
	.form-group {
		margin-bottom: 0.5rem;
	}

	input {
		font-size: 1rem !important;
	}

	#tableVariant th {
		vertical-align: middle;
	}

	#packingUnitTable tr td,
	#tableVariant tr td {
		padding: 0.25rem;
		text-align: center;
	}

	#chechboxField .invalid-feedback {
		text-align: center;
	}

	.is-invalid+.select2-container--bootstrap .select2-selection--single,
	is-invalid span {
		border: 1px solid #f44336;
	}

	.input-group-text {
		padding: 0rem 1rem;
	}

	.mandatory {
		color: #fb6340;
		font-weight: bold;
	}

	.variant {
		border: 1px solid #dee2e6;

	}
</style>
<div class="header bg-primary pb-6">
	<div class="container-fluid">
		<div class="header-body">
			<div class="row align-items-center py-4">
				<div class="col-lg-6 col-7">
					<h6 class="h2 text-white d-inline-block mb-0"><?= $title ?></h6>
				</div>
				<div class="col-lg-6 col-5 col-md-12 text-right">
					<!-- reserve -->
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Page content -->
<div class="container-fluid mt--6">
	<div class="row">
		<div class="col-12">
			<div class="card">
				<!-- Card header -->
				<div class="card-header">
					<div class="row">
						<div class="col-6">
							<h3 class="mb-0"><?= $subtitle ?></h3>
						</div>
					</div>
					<p class="text-sm mb-0">
						<?= $description ?>
					</p>
				</div>
				<div class="card-body pb-0">
					<div class="row">
						<div class="col-12">
							<div class="form-group" id="ref_no">
								<label>No Ref</label>
								<input name="ref_no" class="form-control form-control-sm" value="<?= $data["header"]->ref_no ?>" readonly>
							</div>
							<div class="form-group" id="method_id">
								<label>Metode Penyesuaian <span class="mandatory">*</span></label>
								<input class="form-control form-control-sm" value="<?= $data["header"]->method ?>" readonly>
							</div>
							<div class="form-group" id="method_id">
								<label>Tipe Penyesuaian <span class="mandatory">*</span></label>
								<input class="form-control form-control-sm" value="<?= $data["header"]->type ?>" readonly>
							</div>
							<div class="form-group" id="ref_no">
								<label>User</label>
								<input name="ref_no" class="form-control form-control-sm" value="<?= $data["header"]->user_modified ?>" readonly>
							</div>
							<div class="form-group" id="ref_no">
								<label>Tanggal</label>
								<input name="ref_no" class="form-control form-control-sm" value="<?= $data["header"]->created_at ?>" readonly>
							</div>
						</div>
					</div>
					<div id="variantList" class=" my-2">
						<?php if ($data["header"]->type == "Penambahan") { ?>
							<div class="table-responsive">
								<table class="table table-sm table-bordered" id="tableVariant">
									<thead class="thead-light">
										<th style="width: 5%;">No</th>
										<th style="width: 30%;">Nama Barang</th>
										<th style="width: 5%;">Kode Barang</th>
										<th style="width: 5%;">Brand</th>
										<th>Harga Beli</th>
										<th>Total</th>
										<th style="width: 10%;">Tanggal Kadaluarsa</th>
									</thead>
									<tbody id="variantTableBody">
										<?php
										foreach ($data["detail"] as $key => $value) {
										?>
											<tr>
												<td><?= $key + 1 ?></td>
												<td style="text-align: left;"><?= $value->nama ?></td>
												<td><?= $value->variant_code ?></td>
												<td><?= $value->brand_name ?></td>
												<td><?= "Rp " . number_format($value->buy_price, 0) ?></td>
												<td><?= $value->total_display ?></td>
												<td><?= strtotime($value->expired_date) > 0 ? $value->expired_date : "-" ?></td>
											</tr>
										<?php
										}
										?>
									</tbody>
								</table>
							</div>
						<?php } else { ?>
							<div class="table-responsive">
								<table class="table table-sm table-bordered" style="width: 100%;" id="tableVariant">
									<thead class="thead-light text-center">
										<tr style="vertical-align : middle;text-align:center;">
											<th style="width: 1%;" rowspan="2">No</th>
											<th rowspan="2">Nama Barang</th>
											<th rowspan="2">Kode Barang</th>
											<th rowspan="2">Nama Merk</th>
											<th rowspan="2">Tipe Penyesuaian</th>
											<th colspan="4" style="width: 30%;">Sebelum Penyesuaian</th>
											<th colspan="4" style="width: 30%;">Setelah Penyesuaian</th>
										</tr>
										<tr>
											<th>Jumlah</th>
											<th>Harga Beli</th>
											<th>Tanggal Kadaluarsa</th>
											<th>Total</th>
											<th>Jumlah</th>
											<th>Harga Beli</th>
											<th>Tanggal Kadaluarsa</th>
											<th>Total</th>
										</tr>
									</thead>
									<tbody id="variantTableBody">
										<?php
										$subTotalAfter = 0;
										$subTotalBefore = 0;
										foreach ($data["detail"] as $key => $value) {
											$totalBefore = $value->buy_price_before * $value->total_stock_before;
											$totalAfter = $value->buy_price * $value->total;
											$subTotalAfter += $totalAfter;
											$subTotalBefore += $totalBefore;
										?>
											<tr>
												<td><?= $key + 1 ?></td>
												<td style="text-align: left;"><?= $value->nama ?></td>
												<td><?= $value->variant_code ?></td>
												<td><?= $value->brand_name ?></td>
												<td><?= $value->type ?></td>
												<td><?= $value->total_stock_display_before ?></td>
												<td><?= "Rp " . number_format($value->buy_price_before, 0) ?></td>
												<td><?= strtotime($value->expired_date) > 0 ? $value->expired_date_before : "-" ?></td>
												<td><?= "Rp " . number_format($totalAfter,0) ?></td>
												<?php if ($value->type == "Penghapusan") { ?>
													<td>-</td>
													<td>-</td>
													<td>-</td>
													<td>0</td>
												<?php } else { ?>
													<td><?= $value->total_display ?></td>
													<td><?= "Rp " . number_format($value->buy_price, 0) ?></td>
													<td><?= strtotime($value->expired_date) > 0 ? $value->expired_date : "-" ?></td>
													<td><?= "Rp ". number_format($totalAfter,0) ?></td>
												<?php } ?>
											</tr>
										<?php
										}
										?>
									</tbody>
									<tfoot>
										<tr>
											<td colspan="8">Total</td>
											<td><?= "Rp " . number_format($subTotalBefore) ?></td>
											<td colspan="3"></td>
											<td><?= "Rp " . number_format($subTotalAfter) ?></td>
										</tr>
									</tfoot>
								</table>
							</div>
						<?php } ?>
					</div>
					<div class="form-group" id="remarks">
						<label>Catatan</label>
						<textarea name="remarks" id="taRemarks" rows="2" class="form-control form-control-sm" resize="none" readonly> <?= $data["header"]->remarks ?> </textarea>
					</div>
				</div>
				<div class="card-footer pt-2" style="border-width: 0px;">
					<div class="row text-right">
						<div class="col-12">
							<button class="btn btn-secondary" onclick="javascript:history.back()">Kembali</button>
							<button class="btn btn-secondary" onclick="javascript:history.back()">Print</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	$(document).ready(function() {
		hideLoaderScreen();
	});
</script>
