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
								<div class="form-group" id="ref_no">
									<label>No Ref</label>
									<input name="ref_no" class="form-control form-control-sm" value="<?= generateRefNo('add'); ?>" readonly>
								</div>
								<div class="form-group" id="method_id">
									<label>Metode Penyesuaian <span class="mandatory">*</span></label>
									<select name="method_id" class="form-control form-control-sm">
										<!-- <option>Upload File</option> -->
										<option value="Aplikasi">Via Aplikasi</option>
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
	const getProductUrl = <?= json_encode(base_url('stock/getListProducts')) ?>;
	const saveUrl = <?= json_encode(base_url('stock/saveStock')) ?>;
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
		console.log(data);
		if ($(`#variantRow${data.id}`).length) {
			let element = $(`#variantRow${data.id} input[name="amount[]"]`);
			let num = parseInt(element.val(), 10);
			element.val(num + 1);
		} else {
			let option = data.packing_units.map(e => {
				return `<option value="${e.id}">${e.text}</option>`
			})
			let html = `<tr id="variantRow${data.id}">
							<td>
								<input type="text" class="form-control form-control-sm" value="${data.product}" disabled>
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
									<input type="text" name="buy_price[]" class="form-control form-control-sm price">
								</div>
							</td>
							<td id="expiry_input${data.id}"><input type="date" name="expiry_date[]" class="form-control form-control-sm"><input type="hidden" name="stockId[]"></td>
							<td><a href="#" class="badge badge-pill badge-danger badge-sm" data-toggle="tooltip" data-placement="top" title="Hapus Barang" onclick="deleteRow(${data.id})"><i class="fa fa-trash"></i></a></td>
						</tr>`
			$('#variantTableBody').append(html);
			$('.price').mask('000,000,000,000,000', {
				reverse: true
			});
		}
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
				console.log(result);
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
		})
	})

	/*
	const remove = $('#btn_remove');
	const table = $('#table');
	const modal = $('#modal-form');
	const urlSave = 'saveBarang';
	const urlDelete = 'deleteUser';
	const urlActiveNonactive = 'setActiveUser';
	const urlGetRoleList = 'getRoleList';
	const urlGetBrandList = '/simpati/master/getBrandList';
	const urlGetSingleUser = 'getSingleUser';
	const elBrandDiv = `<label>Merek yang dipegang</label>
						<div id="cbBrand"></div>`

	const selectBrand = $('#brandProduct');
	const selectPacking = $('.def-packing');
	const variantInput = $('#variantInput');
	const variantCb = $('#variantCB');
	const variantComp = $('#variant');
	const listVariantComp = $('#variantList');
	const variantTableBody = $('#variantTableBody');
	const packingUnitTableBody = $('#packingUnitTableBody');
	const packingUnitTable = $('#packingUnitTable');
	const sellingMethod = $('input[name=selling_method]');
	const sellingValue = $('input[name=selling_method_value]');
	const form = $('#form');
	$(document).ready(function() {
		$.fn.select2.defaults.set("theme", "bootstrap");
		hideLoaderScreen();
		selectBrand.select2({
			ajax: {
				url: brandListUrl,
				dataType: 'json',
				delay: 350,
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
			placeholder: 'Cari Merek',
			allowClear: true,
			language: "id"
			// minimumInputLength: 2,
		})
		refreshSelect2();
	});
	$(document).on("keydown", ":input:not(textarea):not(:submit):not(#variant input)", function(event) {
		if (event.key == "Enter") {
			event.preventDefault();
		}
	});

	function getSelectPackingIdValue() {
		let res = $("select[name='packing_id[]']").map(function() {
			return $(this).val() === '' ? 0 : $(this).val();
		}).get();
		return res;
	}

	function refreshSelect2() {
		let selectedPack = getSelectPackingIdValue();
		$('.def-packing').select2({
			ajax: {
				url: packingListSelectUrl,
				dataType: 'json',
				delay: 350,
				data: function(params) {
					return {
						q: params.term,
						page: params.page,
						selected: selectedPack.join(','),
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
			placeholder: 'Cari Unit Kemasan',
			language: "id"
			// minimumInputLength: 2,
		})
		$('.def-packing').on('select2:select', function(e) {
			refreshSelect2()
		})
	}

	function addPackingUnitRow() {
		let numRows = packingUnitTableBody.prop('rows').length;
		let value = getSelectPackingIdValue();
		if (!value.includes(0)) {
			let html = `
					<tr>
						<td>
							<span class="order">${numRows+1}</span>
						</td>
						<td>
							<select name="packing_id[]" class="form-control select2-single def-packing" form-control-sm>
								<option></option>
							</select>
						</td>
						<td>
							<button type="button" id="removePackingUnitRow" class="btn btn-primary btn-sm remove-packing-row">-</button>
						</td>
					</tr>`;
			packingUnitTableBody.append(html);
			console.log('tesst')
			refreshSelect2();
		} else {
			Toast.fire({
				type: "error",
				title: "Pilih kemasan terlebih dahulu",
			});
		}
	}

	packingUnitTable.on('click', '.remove-packing-row', function(e) {
		refreshSelect2();
		let numRows = packingUnitTableBody.prop('rows').length;
		if (numRows > 1) {
			$(this).closest('tr').remove();
			$('#packingUnitTableBody td .order').text(function(i) {
				return i + 1;
			});
		}
	})

	variantCb.click(function() {
		if (this.checked) {
			console.log('Cek');
			checkedVariant();
		} else {
			unCheckedVariant();
			console.log('uncek')
		}
	})

	function checkedVariant() {
		variantComp.removeClass('d-none');
		listVariantComp.removeClass('d-none');
		variantInput.tagsinput('focus');
		$('#variantTableBody input').prop('disabled', false);
	}

	function unCheckedVariant() {
		variantComp.addClass('d-none');
		listVariantComp.addClass('d-none');
		$('#variantTableBody input').prop('disabled', true);
	}

	function addVariantRow(variant) {
		let html = `
			<tr id="tr_${variant}">
				<td><input type="text" name="variant_code[]" class="form-control form-control-sm no-space-key" onkeydown="return event.keyCode!=32"></td>
				<td><input type="text" name="variant_name[]" class="form-control form-control-sm" value="${variant}" readonly></td>
				<td><input type="text" name="variant_description[]" class="form-control form-control-sm"></td>
				<td><input type="number" name="variant_limit[]" class="form-control form-control-sm"></td>
			</tr>
		`;
		variantTableBody.append(html);
	}

	function removeVariantRow(variant) {
		$(`#tr_${variant}`).remove();
	}

	sellingMethod.change(function() {
		$('input[name="selling_method_value"]').prop('readonly', false);
		sellingValue.val('');
		let marginHtml = `
						<div class="input-group-append">
							<span class="input-group-text"><small class="font-weight-bold">%</small></span>
						</div>
		`;
		let priceHtml = `
						<div class="input-group-prepend">
							<span class="input-group-text"><small class="font-weight-bold">Rp. </small></span>
						</div>
		`;
		if (this.value === 'margin') {
			$('#sellingMethodPrice').css('text-decoration', 'line-through');
			$('#sellingMethodMargin').css('text-decoration', '');
			$('.input-group-prepend').remove();
			$(`${marginHtml}`).appendTo($('.input-group-merge'));
			sellingValue.mask('000', {
				reverse: true
			});
		} else {
			$('#sellingMethodMargin').css('text-decoration', 'line-through');
			$('#sellingMethodPrice').css('text-decoration', '');
			$('.input-group-append').remove();
			$(`${priceHtml}`).prependTo($('.input-group-merge'));
			sellingValue.mask('000.000.000.000.000', {
				reverse: true
			});
		}
	})

	variantInput.on('itemAdded', function(event) {
		console.log(event.item);
		addVariantRow(event.item);
	});
	variantInput.on('itemRemoved', function(event) {
		console.log(event.item);
		removeVariantRow(event.item);
	});

	form.on('submit', function(e) {
		e.preventDefault();
		removeClassValidation();
		let formData = new FormData(this);
		$.ajax({
			url: urlSave,
			data: formData,
			type: "POST",
			enctype: "multipart/form-data",
			processData: false, // Important!
			contentType: false,
			success: function(result) {
				console.log(result);
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
		})
	})

	modal.on('hidden.bs.modal', function() {
		$('input[name="id"]').remove();
		$("input").prop('readonly', false)
		removeClassValidation();
		$(`#brandDiv`).html('');
	})

	function togglePassword() {
		let comp = $("input[name='password'],input[name='password_confirmation']");
		comp.attr('type') == 'password' ? comp.attr('type', 'text') : comp.attr('type', 'password')
	}
	*/

	/*
	function editForm(id) {
		showLoaderScreen();
		$.ajax({
			url: `${urlGetSingleUser}?id=${id}`,
			type: 'get',
			dataType: 'json',
			success: function(res) {
				if (res.status) {
					newForm(true);
					data = res.data;
					for (let [key, val] of Object.entries(data)) {
						$(`input[name=${key}]`).val(val);
					}
					data.role.forEach(role => {
						$(`#cbRole${role}`).prop('checked', true);
					});
					$(`input[name="username"]`).prop('readonly', true);
					$('#ff').append(`<input type="hidden" name="id" value="${data.id}" id="id_form">`)
					if (data.role.includes(baKey)) {
						$(`#brandDiv`).html(elBrandDiv);
						fetchList(urlGetBrandList, 'brand_id[]', 'cbBrand', 'brand', data.brand);
					}
				} else {
					Toast.fire({
						type: "error",
						title: "" + result.message + ".",
					});
				}
			},
			complete: function() {
				hideLoaderScreen();
			}
		})
	}
	*/
</script>
