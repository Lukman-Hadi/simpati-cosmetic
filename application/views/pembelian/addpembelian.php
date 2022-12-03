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
								<div class="form-group" id="invoice_no">
									<label>No Faktur</label>
									<input name="invoice_no" class="form-control form-control-sm" required>
								</div>
								<div class="form-group" id="trans_date">
									<label>Tanggal <span class="mandatory">*</span></label>
									<input name="trans_date" class="form-control form-control-sm" type="date">
								</div>
								<div class="form-group" id="supplier_id">
									<label>Supplier <span class="mandatory">*</span></label>
									<select id="selectSupplier" name="supplier_id" class="form-control form-control-sm" required>
										<option></option>
									</select>
								</div>
								<div class="form-group">
									<label>Pilih Barang <span class="mandatory">*</span></label>
									<select id="selectProduct" class="form-control select2-single" form-control-sm>
										<option></option>
									</select>
								</div>
							</div>
						</div>
						<div id="variantList" class=" my-2">
							<div class="table-responsive">
								<table class="table table-sm table-bordered" id="tableVariant">
									<thead class="thead-light">
										<th style="width: 30%;">Nama Barang</th>
										<th style="width: 10%;">Satuan Kemasan</th>
										<th style="width: 5%;">Jumlah</th>
										<th>Harga Beli</th>
										<th style="width: 10%;">Tanggal Kadaluarsa</th>
										<th></th>
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
	const selectProduct = $('#selectProduct')
	const selectSupplier = $('#selectSupplier')
	const getSupplierUrl = <?= json_encode(base_url('master/getSupplierListSelect')) ?>;
	const getProductUrl = <?= json_encode(base_url('stock/getListProducts')) ?>;
	const saveUrl = <?= json_encode(base_url('pembelian/savePembelian')) ?>;
	const testUrl = <?= json_encode(base_url('stock/getExpiryDate')) ?>;
	const form = $('#form');
	$(document).ready(function() {
		$.fn.select2.defaults.set("theme", "bootstrap");
		hideLoaderScreen();
		selectProduct.select2({
			ajax: {
				url: getProductUrl,
				dataType: 'json',
				delay: 500,
				data: function(params) {
					return {
						q: params.term,
						page: params.page,
						limit: 10
					};
				},
				processResults: function(data, params) {
					params.page = params.page || 1;
					if (data.total == 1) {
						appendVariantRow(data.items[0]);
					}
					return {
						results: data.items,
						pagination: {
							more: (params.page * 10) < data.total
						}
					};
				},
				cache: true
			},
			placeholder: 'Cari Barang',
			allowClear: true,
			language: "id",
			minimumInputLength: 4
		});
		selectSupplier.select2({
			ajax: {
				url: getSupplierUrl,
				dataType: 'json',
				delay: 500,
				data: function(params) {
					return {
						q: params.term,
						page: params.page,
						limit: 10
					};
				},
				processResults: function(data, params) {
					params.page = params.page || 1;
					return {
						results: data.items,
						pagination: {
							more: (params.page * 10) < data.total
						}
					};
				},
				cache: true
			},
			placeholder: 'Cari Supplier',
			allowClear: true,
			language: "id"
		})
		// refreshSelect2();
	});
	$(document).on("keydown", ":input:not(textarea):not(:submit)", function(event) {
		if (event.key == "Enter") {
			event.preventDefault();
		}
	});
	selectProduct.on('select2:select', function(e) {
		let data = e.params.data;
		appendVariantRow(data);
	});

	function appendVariantRow(data) {
		selectProduct.val(null).trigger('change');
		selectProduct.select2('close');
		let idRow = data.id;
		if ($(`#variantRow${data.id}`).length) {
			idRow = data.id + 'd' + Math.floor(Math.random() * 10000);
		}
		let option = data.packing_units.map(e => {
			return `<option value="${e.id}">${e.text}</option>`
		})
		let html = `<tr id="variantRow${idRow}">
							<td>
								<input name="variant_name[]" type="text" class="form-control form-control-sm" value="${data.product}" readonly>
								<input name="variant_id[]" type="hidden" class="form-control form-control-sm" value="${data.id}" readonly>
							</td>
							<td><select name="packing_id[]" class="form-control form-control-sm">
								${option}
							</select></td>
							<td><input type="number" name="amount[]" class="form-control form-control-sm" min="1" value="1"></td>
							<td><div class="input-group input-group-merge">
									<div class="input-group-prepend">
										<span class="input-group-text"><small class="font-weight-bold">Rp. </small></span>
									</div>
									<input type="text" name="buy_price[]" value="${data.base_price}" class="form-control form-control-sm price">
								</div>
							</td>
							<td id="expiry_input${idRow}"><input type="date" name="expiry_date[]" class="form-control form-control-sm"><input type="hidden" name="stockId[]"></td>
							<td><a href="#" class="badge badge-pill badge-danger badge-sm" data-toggle="tooltip" data-placement="top" title="Hapus Barang" onclick="deleteRow(${idRow})"><i class="fa fa-trash"></i></a></td>
						</tr>`
		$('#variantTableBody').append(html);
		$('.price').mask('000,000,000,000,000', {
			reverse: true
		});
	}

	function deleteRow(id) {
		$(`#variantRow${id}`).remove();
	}

	function onChangeMethod(id) {
		let selectElement = $(`#variantRow${id} select[name="method[]"]`).val();
		if (selectElement === 'min') {
			ajaxCall(`${testUrl}?id=${id}`).done(function(result) {
				hideLoaderScreen();
				if (result.status) {
					let option = '';
					result.data.map(v => {
						option += `<option value="${v.id}">${v.expired_date}</option>`
					});
					let html = `<input type="hidden" name="expiry_date[]" class="form-control form-control-sm"><select name="stockId[]" class="form-control form-control-sm"> ${option} </select>`
					$(`#expiry_input${id}`).html(html);
				} else {
					$(`#expiry_input${id}`).append('<input type="hidden" name="validation[]" value="1">')
					Toast.fire({
						type: "error",
						title: result.message,
					});
				}
			}).fail(function() {
				console.log('failll')
			})
		} else {
			$(`#expiry_input${id} input[name="validation[]"]`).remove();
			$(`#expiry_input${id}`).html(`<input type="date" name="expiry_date[]" class="form-control form-control-sm"><input type="hidden" name="stockId[]">`);
		}
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
					hideLoaderScreen();
					window.setTimeout(function() {
						window.location.href = "<?= base_url('/pembelian/listpembelian') ?>";
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
