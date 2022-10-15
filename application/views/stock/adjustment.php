<style>
	.form-group {
		margin-bottom: 0.5rem;
	}

	input {
		font-size: 1rem !important;
	}

	#packingUnitTable tr td,
	#tableVariant tr td {
		padding: 0.25rem;
		text-align: center;
	}

	#tableVariant th {
		vertical-align: middle;
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
				<form action="#" method="POST" id="form">
					<div class="card-body pb-0">
						<div class="row">
							<div class="col-12">
								<div class="form-group" id="ref_no">
									<label>No Ref</label>
									<input name="ref_no" class="form-control form-control-sm" value="<?= generateRefNo('py'); ?>" readonly>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="table-responsive py-2 px-4">
								<table id="table" data-toggle="table" data-toolbar="#toolbar" data-pagination="true" data-search="true" data-ajax="ajaxRequest" data-click-to-select="false" class="table table-sm" data-side-pagination="server" data-page-list="[5,10]" data-show-refresh="true" data-show-columns="true" data-show-columns-toggle-all="true">
									<thead class="thead-light text-center">
										<tr>
											<!-- <th data-width="2" data-width-unit="%" data-checkbox="true"></th> -->
											<th data-field="nama" data-width="25" data-width-unit="%" class="proper-case px-1">Nama Barang</th>
											<th data-field="variant_code" class="px-1" data-width="10" data-width-unit="%">Kode Barang</th>
											<th data-field="brand_name" class="px-1" data-width="10" data-width-unit="%">Merek</th>
											<th data-field="buy_price" data-width="15" data-width-unit="%" class="px-1" data-formatter="sellValueFormatter">Harga Beli</th>
											<th data-field="total_unit" class="p-0" data-align="center" data-formatter="unitFormatter">Jumlah Unit</th>
											<th data-field="total_stock" class="p-0" data-align="center">Jumlah Total Barang</th>
											<th data-field="expiry_date" class="p-0" data-align="center">Tanggal Expired</th>
											<th data-field="action" data-width="10" data-width-unit="%" data-formatter="actionFormatter">Action</th>
										</tr>
									</thead>
								</table>
							</div>
						</div>
						<div id="variantList" class=" my-2">
							<div class="table-responsive">
								<table class="table table-sm table-bordered" id="tableVariant">
									<thead class="thead-light text-center">
										<tr style="vertical-align : middle;text-align:center;">
											<th style="width: 30%;" rowspan="2">Nama Barang</th>
											<th style="width: 10%;" rowspan="2">Kode Barang</th>
											<th style="width: 10%;" rowspan="2">Tipe Penyesuaian</th>
											<th style="width: 10%;" colspan="3">Sebelum Penyesuaian</th>
											<th style="width: 10%;" colspan="3">Setelah Penyesuaian</th>
											<th rowspan="2">Action</th>
										</tr>
										<tr>
											<th style="width: 5%;">Jumlah</th>
											<th>Harga Beli</th>
											<th style="width: 10%;">Tanggal Kadaluarsa</th>
											<th style="width: 5%;">Jumlah</th>
											<th>Harga Beli</th>
											<th style="width: 10%;">Tanggal Kadaluarsa</th>
										</tr>
									</thead>
									<tbody id="variantTableBody">

									</tbody>
								</table>
							</div>
						</div>
						<div class="form-group" id="remarks">
							<label>Catatan</label>
							<textarea name="remarks" id="taRemarks" rows="2" class="form-control form-control-sm" resize="none"></textarea>
						</div>
					</div>
					<div class="card-footer pt-2" style="border-width: 0px;">
						<div class="row text-right">
							<div class="col-12">
								<button class="btn btn-primary" type="submit">Simpan</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<script>
	const saveUrl = <?= json_encode(base_url('stock/saveAdjusmentStock')) ?>;
	const form = $('#form');
	const table = $('#table');
	let dataId = [];


	$(document).ready(function() {
		$.fn.select2.defaults.set("theme", "bootstrap");
		hideLoaderScreen();
		$('.price').mask('000,000,000,000,000', {
			reverse: true
		});
	});
	$(document).on("keydown", ":input:not(textarea):not(:submit)", function(event) {
		if (event.key == "Enter") {
			event.preventDefault();
		}
	});

	function refreshTable(params) {
		table.bootstrapTable('refresh');
	}

	function ajaxRequest(params) {
		let id = dataId.join(",");
		let url = 'getListStockForAdjustment'
		$.get(url + '?' + 'id=' + id + '&' + $.param(params.data)).then(function(res) {
			params.success(res)
		})
	}

	function appendRow(data) {
		dataId = [...dataId, Number(data.stock_id)];
		let html = `
					<tr id="variantRow${data.stock_id}">
						<input class="form-control form-control-sm" name="stock_id[]" type="hidden" value="${data.stock_id}">
						<input class="form-control form-control-sm" name="packing_id[]" type="hidden" value="${data.packing_id}">
						<input class="form-control form-control-sm" name="variant_id[]" type="hidden" value="${data.variant_id}">
						<td><input class="form-control form-control-sm" type="text" value="${data.nama}" disabled></td>
						<td><input class="form-control form-control-sm" type="text" value="${data.variant_code}" disabled></td>
						<td><input class="form-control form-control-sm" name="type[]" type="text" value="Penyesuaian" readonly></td>
						<td><input class="form-control form-control-sm" type="number" name="total_before[]" value="${data.total_stock}" readonly></td>
						<td><input class="form-control form-control-sm" type="text" name="buy_price_before[]" value="${(Number(data.buy_price)).toLocaleString("en-US")}" readonly></td>
						<td><input class="form-control form-control-sm" type="text" name="expiry_date_before[]" value="${data.expiry_date}" readonly></td>
						<td><input class="form-control form-control-sm" type="number" name="total[]" min="1" value="1"></td>
						<td><input class="form-control form-control-sm price no-validate" type="number" name="buy_price[]" value="0"></td>
						<td><input class="form-control form-control-sm" name="expiry_date[]" type="date"></td>
						<td><a href="javascript:void(0);" role="button" class="badge badge-pill badge-danger badge-sm" data-toggle="tooltip" data-placement="top" onclick="deleteRow(${data.stock_id})" title="Hapus Barang"><i class="fa fa-trash"></i></a></td>
					</tr>`
		$('#variantTableBody').append(html);
		$('.price').mask('000.000.000.000.000', {
			reverse: true
		});
		refreshTable();
	}

	function appendRowDelete(data) {
		dataId = [...dataId, Number(data.stock_id)];
		let html = `
		<tr id="variantRow${data.stock_id}">
			<input class="form-control form-control-sm" name="stock_id[]" type="hidden" value="${data.stock_id}">
			<input class="form-control form-control-sm" name="packing_id[]" type="hidden" value="${data.packing_id}">
			<input class="form-control form-control-sm" name="variant_id[]" type="hidden" value="${data.variant_id}">
			<td><input class="form-control form-control-sm" type="text" value="${data.nama}" disabled></td>
			<td><input class="form-control form-control-sm" type="text" value="${data.variant_code}" disabled></td>
			<td><input class="form-control form-control-sm" name="type[]" type="text" value="Penghapusan" readonly></td>
			<td><input class="form-control form-control-sm" type="number" name="total_before[]" value="${data.total_stock}" readonly></td>
			<td><input class="form-control form-control-sm" type="text" name="buy_price_before[]" value="${(Number(data.buy_price)).toLocaleString("en-US")}" readonly></td>
			<td><input class="form-control form-control-sm" type="text" name="expiry_date_before[]" value="${data.expiry_date}" readonly></td>
			<td><input class="form-control form-control-sm" type="number" name="total[]" min="1" value="0" readonly></td>
			<td><input class="form-control form-control-sm price" type="number" name="buy_price[]" value="0" readonly></td>
			<td><input class="form-control form-control-sm" name="expiry_date[]" type="text" value="-" readonly></td>
			<td><a href="javascript:void(0);" role="button" class="badge badge-pill badge-danger badge-sm" data-toggle="tooltip" data-placement="top" onclick="deleteRow(${data.stock_id})" title="Hapus Barang"><i class="fa fa-trash"></i></a></td>
		</tr>
		`
		$('#variantTableBody').append(html);
		refreshTable();
	}

	function deleteRow(id) {
		console.log({
			dataId,
			id
		})
		dataId = dataId.filter(e => e !== id);
		$(`#variantRow${id}`).remove();
		refreshTable();
	}

	// function onChangeMethod(id) {
	// 	let selectElement = $(`#variantRow${id} select[name="method[]"]`).val();
	// 	if (selectElement === 'min') {
	// 		ajaxCall(`${testUrl}?id=${id}`).done(function(result) {
	// 			hideLoaderScreen();
	// 			if (result.status) {
	// 				let option = '';
	// 				result.data.map(v => {
	// 					option += `<option value="${v.id}">${v.expired_date}</option>`
	// 				});
	// 				let html = `<input type="hidden" name="expiry_date[]" class="form-control form-control-sm"><select name="stockId[]" class="form-control form-control-sm"> ${option} </select>`
	// 				$(`#expiry_input${id}`).html(html);
	// 			} else {
	// 				$(`#expiry_input${id}`).append('<input type="hidden" name="validation[]" value="1">')
	// 				Toast.fire({
	// 					type: "error",
	// 					title: result.message,
	// 				});
	// 			}
	// 		}).fail(function() {
	// 			console.log('failll')
	// 		})
	// 	} else {
	// 		$(`#expiry_input${id} input[name="validation[]"]`).remove();
	// 		$(`#expiry_input${id}`).html(`<input type="date" name="expiry_date[]" class="form-control form-control-sm"><input type="hidden" name="stockId[]">`);
	// 	}
	// }

	function sellValueFormatter(val, row) {
		let res = '';
		if (row.sell_method === '%') {
			res = `${Number(val)}%`;
		} else {
			res = uang.format(val);
		}
		if (row.stock_id == null) return '-';
		return `${res} / ${row.Pack[row.Pack.length-1].unit}`;
	}

	function unitFormatter(val, row) {
		console.log({
			val,
			row
		})
		if (row.stock_id == null) return '-';
		let res = '';
		row.Pack.forEach(arr => {
			if (arr.total != 0) {
				res += ` ${arr.total} ${arr.unit}`
			}
		});
		return res;
	}

	function actionFormatter(val, row) {
		return row.stock_id !== null ?
			`
			<div class="col-12 p-0 text-center">
			<div class="row d-flex justify-content-center">
				<a href="javascript:void(0);" role="button" class="badge badge-pill badge-primary badge-sm" data-toggle="tooltip" data-placement="top" title="Edit Stock" onclick="appendRow(JSON.parse('${JSON.stringify(row).replace(/'/g, '&apos;').replace(/"/g, '&quot;')}'))"><i class="fa fa-edit"></i></a>
				<a href="javascript:void(0);" role="button" class="badge badge-pill badge-danger badge-sm" data-toggle="tooltip" data-placement="top" title="Hapus Stock" onclick="appendRowDelete(JSON.parse('${JSON.stringify(row).replace(/'/g, '&apos;').replace(/"/g, '&quot;')}'))"><i class="fa fa-trash"></i></a>
			</div>
			</div>
			` :
			`
			<div class="col-12 p-0 text-center">
			<div class="row d-flex justify-content-center">
				<a href="javascript:void(0);" class="badge badge-pill badge-primary badge-sm" data-toggle="tooltip" data-placement="top" title="Edit Stock" onclick="appendRow(JSON.parse('${JSON.stringify(row).replace(/'/g, '&apos;').replace(/"/g, '&quot;')}'))"><i class="fa fa-edit"></i></a>
			</div>
			</div>
			`
	}

	form.on('submit', function(e) {
		e.preventDefault();
		removeClassValidation();
		let formData = new FormData(this);
		$.ajax({
			url: saveUrl,
			data: formData,
			type: "POST",
			enctype: "multipart/form-data",
			processData: false, // Important!
			contentType: false,
			success: function(result) {
				if (result.status) {
					Toast.fire({
						type: "success",
						title: "" + result.message + ".",
					});
					hideLoaderScreen();
					window.setTimeout(function() {
						window.location.href = "<?= base_url('/barang/listbarang') ?>";
					}, 1000);
				} else {
					if (result.message == "validationError") {
						let err = result.data;
						for (let [key, val] of Object.entries(err)) {
							addClassValidation(key, val);
						}
						$(".form-control").addClass("is-valid");
					} else {
						Toast.fire({
							type: "error",
							title: "" + result.message + ".",
						});
					}
				}
			}
		})
	})
</script>
