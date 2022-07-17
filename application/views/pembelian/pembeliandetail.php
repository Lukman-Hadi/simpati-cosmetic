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
					<h6 class="h2 text-white d-inline-block mb-0">Invoice No : <?= $data["header"]->invoice_no ?></h6><br>
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
				<div class="card-header border-0">
					<div class="row">
						<div class="col-sm-3 col-md-3">
							No Invoice
						</div>
						<div class="col-sm-9 col-md-9">
							: <?= $data["header"]->invoice_no ?>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-3 col-md-3">
							Supplier
						</div>
						<div class="col-sm-9 col-md-9">
							: <?= $data["header"]->supplier ?>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-3 col-md-3">
							Tanggal
						</div>
						<div class="col-sm-9 col-md-9">
							: <?= $data["header"]->trans_date ?>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-3 col-md-3">
							User
						</div>
						<div class="col-sm-9 col-md-9">
							: <?= $data["header"]->user_modified ?>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-3 col-md-3">
							Grand Total
						</div>
						<div class="col-sm-9 col-md-9">
							: Rp. <?= number_format($data["header"]->grand_total, 2, ',', '.') ?> (<?= terbilang($data["header"]->grand_total) ?> Rupiah)
						</div>
					</div>
				</div>
				<div class="card-body pb-0">
					<div class="table-responsive">
						<table class="table table-bordered align-items-center table-hover table-sm">
							<thead class="thead-light text-center">
								<tr>
									<th width="1%">No</th>
									<th>Nama Item</th>
									<th width="10%">Nama Brand</th>
									<th width="10%">Total Item</th>
									<th width="10%">Total QTY</th>
									<th width="10%">Harga</th>
									<th width="10%">SubTotal</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$subtotal=0;
								foreach ($data["detail"] as $key => $value) {
									$subtotal += $value->subtotal;
								?>
									<tr>
										<td><?= $key + 1 ?></td>
										<td style="text-align: left;"><?= $value->variant_name ?></td>
										<td><?= $value->brand_name ?></td>
										<td><?= $value->qty_pack ?></td>
										<td><?= $value->qty_total ?></td>
										<td><?= "Rp " . number_format($value->price, 0) ?></td>
										<td><?= "Rp " . number_format($value->subtotal, 0) ?></td>
									</tr>
								<?php
								}
								?>
							</tbody>
							<tfoot>
								<tr>
									<td colspan="6">Total</td>
									<td><?= "Rp " . number_format($subtotal) ?></td>
								</tr>
							</tfoot>
						</table>
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
