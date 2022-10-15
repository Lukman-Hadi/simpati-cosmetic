<style>
	.form-group {
		margin-bottom: 0.5rem;
	}

	input {
		font-size: 1rem !important;
	}

	.is-invalid td {
		color: #f44336;
		text-decoration: solid;
	}

	.is-invalid input[name="amount_unit[]"] {
		border-color: #fb6340;
	}

	#table tr td,
	#tableVariant tr td {
		padding: 0.25rem;
		text-align: left;
		vertical-align: middle !important;
		word-wrap: break-word;
		white-space: normal !important;
	}

	#chechboxField .invalid-feedback {
		text-align: center;
	}

	#mainSection,
	#tableStockSection {
		-webkit-transition: all 0.5s ease;
		-moz-transition: all 0.5s ease;
		-o-transition: all 0.5s ease;
		transition: all 0.5s ease;
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
		<div class="col-xl-12 col-lg-12" id="mainSection">
			<div class="card">
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
									<label>No Inovice <span class="mandatory">*</span></label>
									<input name="invoice_no" class="form-control form-control-sm" value="<?= generateInvoiceNo(); ?>" readonly>
								</div>
								<div class="form-group" id="trans_date">
									<label>Tanggal <span class="mandatory">*</span></label>
									<input name="trans_date" class="form-control form-control-sm" type="date">
								</div>
								<div class="form-group" id="customer_id">
									<label>Pilih Customer <span class="mandatory">*</span></label>
									<select id="selectCustomer" name="customer_id" class="form-control select2-single" form-control-sm>
										<option></option>
									</select>
								</div>
								<div class="form-group">
									<label>Pilih Item </label>
									<select id="selectProduct" class="form-control select2-single" form-control-sm>
										<option></option>
									</select>
								</div>
								<div class="form-group mt-3">
									<label>Lihat Tabel Stock </label>
									<label class="custom-toggle custom-toggle-default" style="vertical-align: middle;">
										<input type="checkbox" id="showTable">
										<span class="custom-toggle-slider rounded-circle"></span>
									</label>
								</div>
							</div>
						</div>
						<div id="variantList" class=" my-2">
							<table class="table table-sm table-bordered" id="tableVariant" style="width: 100%;">
								<thead class="thead-light">
									<th style="width: 30%;">Nama Barang</th>
									<th style="width: 30%;" colspan="2">Jumlah</th>
									<th style="width: 30%;">Harga</th>
									<th style="width: 8%;">subtotal</th>
									<th style="width: 1%;">Exp</th>
									<th style="width: 1%;" class="px-1">action</th>
								</thead>
								<tbody id="variantTableBody">

								</tbody>
							</table>
						</div>
						<div class="form-group" id="grand_total">
							<label>Grand Total</label>
							<input name="grand_total_d" class="form-control form-control-sm" readonly>
							<input name="grand_total" class="form-control form-control-sm" type="hidden">
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
		<div class="col-xl-12 col-lg-12 d-none" id="tableStockSection">
			<div class="card">
				<div class="card-header">
					<div class="row">
						<div class="col-6">
							<h3 class="mb-0">Item</h3>
						</div>
					</div>
					<p class="text-sm mb-0">
						Silahkan gunakan kolom pilih item dengan mencari berdasarkan nama atau kode item, tabel stok berisi informasi nama harga rekomendasi dan stok tersedia
					</p>
				</div>
				<div class="table-responsive py-2 px-4">
					<table id="table" data-toggle="table" data-toolbar="#toolbar" data-url="getListStockSell" data-pagination="true" data-search="true" data-click-to-select="false" class="table table-sm" data-side-pagination="server" data-page-list="[5,10,15]" data-show-refresh="true" data-show-columns="true" data-show-columns-toggle-all="true">
						<thead class="thead-light text-center">
							<tr>
								<!-- <th data-width="2" data-width-unit="%" data-checkbox="true"></th> -->
								<th data-field="action" data-width="1" data-width-unit="%" data-formatter="actionFormatter">Action</th>
								<th data-field="nama" class="proper-case px-1">Nama Barang</th>
								<th data-field="price" data-width="15" data-width-unit="%" class="px-1" data-formatter="sellValueFormatter">Harga MSRP</th>
								<th data-field="price_dist" data-width="15" data-width-unit="%" class="px-1" data-formatter="sellValueFormatter">Harga Apotik</th>
								<th data-field="expired_date" data-width="5" data-width-unit="%" class="px-1" data-align="center">Tanggal Expired</th>
								<th class="p-0" data-width="5" data-width-unit="%" data-align="center" data-formatter="unitFormatter">Stock Tersedia</th>
							</tr>
						</thead>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	const selectProduct = $('#selectProduct')
	const selectCustomer = $('#selectCustomer')
	const getProductUrl = <?= json_encode(base_url('stock/getListProductsSell')) ?>;
	const saveUrl = <?= json_encode(base_url('penjualan/addPenjualan')) ?>;
	const testUrl = <?= json_encode(base_url('stock/getExpiryDate')) ?>;
	const urlSelectCust = <?= json_encode(base_url('customer/getCustomerListSelect')) ?>;
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
		})
		selectCustomer.select2({
			ajax: {
				url: urlSelectCust,
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
			placeholder: 'Cari Customer',
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

	$(`#showTable`).change(function() {
		if ($(this).is(':checked')) {
			$('#tableStockSection').removeClass('d-none');
			$('#mainSection').removeClass();
			$('#mainSection').addClass('col-xl-12 col-lg-12');
		} else {
			$('#tableStockSection').addClass('d-none');
			$('#mainSection').removeClass();
			$('#mainSection').addClass('col-xl-12 col-lg-12');
		}
	});
	selectProduct.on('select2:select', function(e) {
		let data = e.params.data;
		appendVariantRow(data);
	});

	function appendVariantRow(data) {
		selectProduct.val(null).trigger('change');
		selectProduct.select2('close');
		console.log(data);
		if ($(`#variantRow${data.id}`).length) {
			let element = $(`#variantRow${data.id} input[name="amount_unit[]"]`);
			let num = parseInt(element.val(), 10);
			element.val(num + 1).trigger('change');
		} else {
			let option = data.packing_units.map(e => {
				return `<option value="${e.id}">${e.text}</option>`
			})
			let html = `<tr id="variantRow${data.id}" class="">
							<td>
							${data.product}
								<input type="hidden" value="${data.product}" name="variant_name[]" readonly>
								<input type="hidden" value="${data.variant_id}" name="variant_id[]" readonly>
								<input name="stock_id[]" type="hidden" class="form-control form-control-sm" value="${data.id}" readonly>
							</td>
							<td style="width:5rem;">
								<input type="number" name="amount_unit[]" class="form-control form-control-sm" min="1" value="1" onchange="recalculate(${data.id})">
								<input type="hidden" name="amount_total[]" value="1">
								<input type="hidden" name="amount_available[]" value="${data.total_stock}">
							</td>
							<td>
									<select name="packing_id[]" class="form-control form-control-sm" onchange="resetAmountUnit(JSON.parse('${JSON.stringify(data.packing_units).replace(/'/g, '&apos;').replace(/"/g, '&quot;')}'),${data.id})">
									${option}
									</select>
							</td>
							<td><div class="input-group input-group-merge">
									<div class="input-group-prepend">
										<span class="input-group-text"><small class="font-weight-bold">Rp. </small></span>
									</div>
									<input type="text" name="sell_price[]" class="form-control form-control-sm price" value="${data.price}" onkeyup="calculateRow(${data.id})" onchange="calculateRow(${data.id})">
								</div>
							</td>
							<td><input type="hidden" name="sub_total[]" onchange="calculateGrandTotal()"><span class="subtotal">0</span></td>
							<td><span>${data.expired_date}</span></td>
							<td style="text-align: center;"><a href="#" class="badge badge-pill badge-danger badge-sm" data-toggle="tooltip" data-placement="top" title="Hapus Barang" onclick="deleteRow(${data.id})"><i class="fa fa-trash"></i></a></td>
						</tr>`
			$('#variantTableBody').append(html);
			$(`#variantRow${data.id} input[name="sell_price[]"]`).trigger('change');
			$('.price').mask('000,000,000,000,000', {
				reverse: true
			});
		}
	}

	function recalculate(id) {
		$(`#variantRow${id} select[name="packing_id[]"]`).trigger('change');
	}

	function resetAmountUnit(data, id) {
		let num = Number($(`#variantRow${id} input[name="amount_unit[]"]`).val());
		let selected = $(`#variantRow${id} select[name="packing_id[]"]`).val();
		// let [{
		// 	amount
		// }] = data.filter(p => p.id == selected);
		let amount = 1;
		for (let index = 0; index < data.length; index++) {
			if (Number(selected) !== data[index].id) {
				amount *= data[index].amount;
			} else if (Number(selected) === data[index].id) {
				amount *= data[index].amount;
				break
			}
		}
		let totalAmount = num * amount;
		console.debug({
			data,
			id,
			selected,
			totalAmount,
			num,
			amount
		})
		$(`#variantRow${id} input[name="amount_total[]"]`).val(totalAmount).trigger('change');
		$(`#variantRow${id} input[name="sell_price[]"]`).trigger('change');
	}

	function calculateRow(id) {
		let price = parseFloat(($(`#variantRow${id} input[name="sell_price[]"]`).val()).replace(/,/g, ''));
		let num = Number($(`#variantRow${id} input[name="amount_unit[]"]`).val());
		$(`#variantRow${id} span.subtotal`).text(uang.format(zeroIfNonNum(num) * zeroIfNonNum(price)));
		$(`#variantRow${id} input[name="sub_total[]"]`).val(zeroIfNonNum(num) * zeroIfNonNum(price)).trigger('change');
	}

	function calculateGrandTotal() {
		const arrInput = $("input[name='sub_total[]']").map(function() {
			return $(this).val();
		}).get();
		let grandTotal = 0
		if (arrInput.length > 0) grandTotal = arrInput.reduce((prev, curr) => zeroIfNonNum(prev) + zeroIfNonNum(curr));
		$("input[name='grand_total_d']").val(uang.format(grandTotal));
		$("input[name='grand_total']").val(grandTotal);
	}

	function zeroIfNonNum(n) {
		return isNaN(n) ? 0 : parseFloat(n);
	}

	function validateStock() {
		const arrAvail = $("input[name='amount_available[]']").map(function() {
			return $(this).val();
		}).get();
		const arrAmount = $("input[name='amount_total[]']").map(function() {
			return $(this).val();
		}).get();
		let notValid = [];
		if (arrAvail.length > 0) {
			for (let i = 0; i < arrAvail.length; i++) {
				if (Number(arrAmount[i]) > Number(arrAvail[i])) {
					notValid = [...notValid, i];
				}
			}
		}
		return notValid;
	}

	function actionFormatter(val, row) {
		return row.total_stock > 0 ? `
		<div class="col-12 p-0 text-center">
		<div class="row d-flex justify-content-center">
			<a href="#" href="javascript:void(0);" class="badge badge-pill badge-secondary badge-sm p-1" data-toggle="tooltip" data-placement="top" title="Tambahkan Item" onClick="appendVariantRow(JSON.parse('${JSON.stringify(row).replace(/'/g, '&apos;').replace(/"/g, '&quot;')}'))"><i class="fa fa-plus-circle text-lg"></i></a>
		</div>
		</div>
		` : '-';
	}

	function sellValueFormatter(val, row) {
		let res = '';
		res = uang.format(val);
		if (row.id == null) return '-';
		return `${res} / ${row.Pack[row.Pack.length-1].unit}`;
	}

	function unitFormatter(val, row) {
		if (!row.total_stock > 0) return '-';
		let res = '';
		row.Pack.forEach(arr => {
			if (arr.total != 0) {
				res += ` ${arr.total} ${arr.unit}`
			}
		});
		return res;
	}

	function deleteRow(id) {
		$(`#variantRow${id}`).remove();
		calculateGrandTotal();
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
		showLoaderScreen();
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
						title: result.message,
					});
					window.setTimeout(function() {
						location.reload();
					}, 1000);
				} else {
					if (result.message == "validationError") {
						let err = result.data;
						for (let [key, val] of Object.entries(err)) {
							addClassValidation(key, val);
						}
						$(".form-control").addClass("is-valid");
					} else if (result.message == "validationStock") {
						let err = result.data;
						validationStockError(err);
					} else if (result.message == "validationStock") {
						Toast.fire({
							type: "error",
							title: result.message,
						});
					}
				}
				hideLoaderScreen();
			}
		})
	})

	function validationStockError(err) {
		err.forEach((v, i) => {
			$(`#variantRow${v.id}`).addClass("is-invalid");
		})
		Toast.fire({
			type: "error",
			title: "Item Melebihi Stock.",
		});
	}
</script>
