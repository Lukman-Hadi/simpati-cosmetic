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
							<div class="col-6">
								<div class="form-group" id="nama">
									<label>Nama Barang <span class="mandatory">*</span></label>
									<input type="text" name="nama" class="form-control form-control-sm" placeholder="cth: la tulipe lipstick ">
								</div>
								<div class="form-group" id="product_code">
									<label>Kode Barang <span class="mandatory">*</span></label>
									<input type="text" name="product_code" class="form-control form-control-sm no-space-key" placeholder="cth: ltpelip">
								</div>
								<div class="form-group" id="kode">
									<label>Kode Barcode</label>
									<input type="text" name="barcode" class="form-control form-control-sm" placeholder="scan setelah fokus di kolom ini">
								</div>
								<div class="form-group" id="brand_id">
									<label>Merek Barang <span class="mandatory">*</span></label>
									<select id="brandProduct" name="brand_id" class="form-control select2-single" form-control-sm>
										<option></option>
									</select>
								</div>
								<!-- <div class="form-group">
									<label>Gambar Barang</label>
									<div class="custom-file">
										<input type="file" name="image" class="custom-file-input" id="customFileLang" lang="id">
										<label class="custom-file-label" for="customFileLang">Pilih Gambar</label>
									</div>
								</div> -->
								<div class="form-group" id="limit_primary">
									<label>Limit Peringatan</label>
									<input type="text" name="limit_primary" class="form-control form-control-sm" placeholder="cth: ltpelip">
								</div>
							</div>
							<div class="col-6">
								<div class="form-group" id="kode">
									<label>Metode Harga Jual<span class="mandatory">*</span></label> <br>
									<div class="custom-control custom-radio custom-control-inline">
										<input type="radio" id="sellingMargin" name="selling_method" value="margin" class="custom-control-input">
										<label class="custom-control-label" for="sellingMargin">harga jual ditentukan oleh margin</label>
									</div>
									<div class="custom-control custom-radio custom-control-inline">
										<input type="radio" id="sellingPrice" name="selling_method" value="price" class="custom-control-input">
										<label class="custom-control-label" for="sellingPrice">harga jual ditentukan oleh nilai harga</label>
									</div>
								</div>
								<div class="form-group" id="selling_method_value">
									<label> <span id="sellingMethodPrice">Harga</span> / <span id="sellingMethodMargin"> Margin</span> <span class="mandatory">*</span></label>
									<div class="input-group input-group-merge">
										<input type="text" name="selling_method_value" class="form-control form-control-sm" readonly>
									</div>
								</div>
								<div class="form-group" id="selling_dist">
									<label>Harga Jual Distributor</label>
									<div class="input-group input-group-merge">
										<div class="input-group-prepend">
											<span class="input-group-text"><small class="font-weight-bold">Rp. </small></span>
										</div>
										<input type="text" name="selling_dist" class="form-control form-control-sm">
									</div>
								</div>
								<div class="form-group" id="base_price">
									<label>Harga Modal</label>
									<div class="input-group input-group-merge">
										<div class="input-group-prepend">
											<span class="input-group-text"><small class="font-weight-bold">Rp. </small></span>
										</div>
										<input type="text" name="base_price" class="form-control form-control-sm">
									</div>
								</div>
								<div>
									<div class="form-group row pb-0">
										<label class="col-9 col-form-label pb-0">Satuan Kemasan (Urutan Dari Yang Terbesar)</label>
										<div class="col-3 text-right">
											<!-- <input class="form-control" type="text" value="John Snow" id="example-text-input"> -->
											<button class="btn btn-primary btn-sm mt-2" type="button" onclick="addPackingUnitRow()">+</button>
										</div>
									</div>
									<div class="table-responsive" id="packing_id">
										<table class="table align-items-center table-sm table-bordered" id="packingUnitTable">
											<thead class="thead-light">
												<th style="width: 5%;" class="p-1">Urutan</th>
												<th class="p-1">Kode Unit Kemasan</th>
												<th style="width: 10%;" class="p-1">Aksi</th>
											</thead>
											<tbody id="packingUnitTableBody">
												<tr>
													<td>
														<span class="order">1</span>
													</td>
													<td>
														<select name="packing_id[]" class="form-control select2-single def-packing" form-control-sm>
															<option></option>
														</select>
													</td>
													<td>
														<button type="button" id="removePackingUnitRow" class="btn btn-primary btn-sm remove-packing-row">-</button>
													</td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
						<div class="form-group" id="description">
							<label>Dekripsi Barang</label>
							<textarea name="description" id="taDescription" rows="2" class="form-control" resize="none"></textarea>
						</div>
						<div class="custom-control custom-checkbox mb-3">
							<input class="custom-control-input" name="is_multiple_variant" id="variantCB" type="checkbox">
							<label class="custom-control-label" for="variantCB">Barang ini memiliki banyak variant</label>
						</div>
						<div id="variant" class="d-none" style="border: 1px solid rgb(222, 226, 230);">
							<input type="text" id="variantInput" class="form-control w-100" data-toggle="tags" placeholder="Ketik nama variant lalu tekan enter untuk menambahkan variant" readonly />
						</div>
						<div id="variantList" class="d-none my-2">
							<div class="table-responsive">
								<table class="table table-sm table-bordered" id="tableVariant">
									<thead class="thead-light">
										<th>Kode Varian <span class="mandatory">*</span></th>
										<th>Nama Varian</th>
										<th>Deskripsi</th>
										<th>Limit Peringatan</th>
									</thead>
									<tbody id="variantTableBody">

									</tbody>
								</table>
							</div>
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
	const baKey = <?= json_encode(ID_ROLE_BA); ?>;
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
	// const selectPacking = $('.def-packing');
	const variantInput = $('#variantInput');
	const variantCb = $('#variantCB');
	const variantComp = $('#variant');
	const listVariantComp = $('#variantList');
	const variantTableBody = $('#variantTableBody');
	const packingUnitTableBody = $('#packingUnitTableBody');
	const packingUnitTable = $('#packingUnitTable');
	const brandListUrl = <?= json_encode(base_url('master/getBrandListSelect')) ?>;
	const packingListSelectUrl = <?= json_encode(base_url('master/getPackingList')) ?>;
	const packingChildListSelectUrl = <?= json_encode(base_url('master/getBrandListSelect')) ?>;
	const sellingMethod = $('input[name=selling_method]');
	const sellingValue = $('input[name=selling_method_value]');
	const selllingDist = $('input[name=selling_dist]');
	const basePrice = $('input[name=base_price]');
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
		setupInput();
	});

	function setupInput() {
		selllingDist.mask('000.000.000.000.000', {
			reverse: true
		});
		basePrice.mask('000.000.000.000.000', {
			reverse: true
		});
	}
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
			<tr id="tr_${variant.replace(/[^a-zA-Z0-9]/g, '')}">
				<td><input type="text" name="variant_code[]" class="form-control form-control-sm no-space-key" onkeydown="return event.keyCode!=32"></td>
				<td><input type="text" name="variant_name[]" class="form-control form-control-sm" value="${variant}" readonly></td>
				<td><input type="text" name="variant_description[]" class="form-control form-control-sm"></td>
				<td><input type="number" name="variant_limit[]" class="form-control form-control-sm"></td>
			</tr>
		`;
		variantTableBody.append(html);
	}

	function removeVariantRow(variant) {
		$(`#tr_${variant.replace(/[^a-zA-Z0-9]/g, '')}`).remove();
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
			$('#selling_method_value .input-group-prepend').remove();
			$(`${marginHtml}`).appendTo($('#selling_method_value .input-group-merge'));
			sellingValue.mask('000', {
				reverse: true
			});
		} else {
			$('#sellingMethodMargin').css('text-decoration', 'line-through');
			$('#sellingMethodPrice').css('text-decoration', '');
			$('.input-group-append').remove();
			$(`${priceHtml}`).prependTo($('#selling_method_value .input-group-merge'));
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
		showLoaderScreen();
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
				hideLoaderScreen();
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
</script>
