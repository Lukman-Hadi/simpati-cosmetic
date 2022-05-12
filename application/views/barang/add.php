<style>
	.form-group {
		margin-bottom: 0.5rem;
	}

	input {
		font-size: 1rem !important;
	}

	#chechboxField .invalid-feedback {
		text-align: center;
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
				<form action="#">
					<div class="card-body pb-0">
						<div class="row">
							<div class="col-6">
								<div class="form-group" id="nama">
									<label>Nama Barang <span class="mandatory">*</span></label>
									<input type="text" name="nama" class="form-control form-control-sm" placeholder="cth: la tulipe lipstick ">
								</div>
								<div class="form-group" id="product_code">
									<label>Kode Barang <span class="mandatory">*</span></label>
									<input type="text" name="product_code" class="form-control form-control-sm" placeholder="cth: ltpelip">
								</div>
								<div class="form-group" id="kode">
									<label>Kode Barcode</label>
									<input type="text" name="nama" class="form-control form-control-sm" placeholder="scan setelah fokus di kolom ini">
								</div>
								<div class="form-group" id="brand_id">
									<label>Merek Barang <span class="mandatory">*</span></label>
									<select id="brandProduct" name="brand_id" class="form-control select2-single" form-control-sm>
										<option></option>
									</select>
								</div>
								<div class="form-group" id="brand_id">
									<label>Gambar Barang</label>
									<div class="custom-file">
										<input type="file" class="custom-file-input" id="customFileLang" lang="id">
										<label class="custom-file-label" for="customFileLang">Pilih Gambar</label>
									</div>
								</div>
							</div>
							<div class="col-6">
								<div class="form-group" id="kode">
									<label>Metode Harga Jual<span class="mandatory">*</span></label> <br>
									<div class="custom-control custom-radio custom-control-inline">
										<input type="radio" id="sellingMargin" name="selling_method" class="custom-control-input">
										<label class="custom-control-label" for="sellingMargin">harga jual ditentukan oleh margin</label>
									</div>
									<div class="custom-control custom-radio custom-control-inline">
										<input type="radio" id="sellingPrice" name="selling_method" class="custom-control-input">
										<label class="custom-control-label" for="sellingPrice">harga jual ditentukan oleh nilai harga</label>
									</div>
								</div>
								<div class="form-group" id="product_code">
									<label>Harga / Margin <span class="mandatory">*</span></label>
									<input type="text" name="product_code" class="form-control form-control-sm" placeholder="cth: ltpelip">
								</div>
								<div class="form-group" id="def_packing_id">
									<label>Merek Barang <span class="mandatory">*</span></label>
									<select id="defPacking" name="def_packing_id" class="form-control select2-single" form-control-sm>
										<option></option>
									</select>
								</div>
								<div class="form-group" id="def_sale_packing_id">
									<label>Merek Barang <span class="mandatory">*</span></label>
									<select id="defSalePacking" name="def_sale_packing_id" class="form-control select2-single" form-control-sm>
										<option></option>
									</select>
								</div>
								<div class="form-group" id="def_buy_packing_id">
									<label>Merek Barang <span class="mandatory">*</span></label>
									<select id="defBuyPacking" name="def_buy_packing_id" class="form-control select2-single" form-control-sm>
										<option></option>
									</select>
								</div>
							</div>
						</div>
						<div class="form-group" id="description">
							<label>Dekripsi Barang</label>
							<textarea name="description" id="taDescription" rows="2" class="form-control" resize="none"></textarea>
						</div>
						<div class="custom-control custom-checkbox mb-3">
							<input class="custom-control-input" id="variantCB" type="checkbox">
							<label class="custom-control-label" for="variantCB">Barang ini memiliki banyak variant</label>
						</div>
						<div class="variant">
							<form>
								<input type="text" id="variantInput" class="form-control w-100" data-toggle="tags" placeholder="Ketik nama variant lalu tekan enter untuk menambahkan variant" readonly />
							</form>
						</div>
						<div id="variantList">
							<p>Test</p>
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
	<div class="row">
		<div class="col-6">
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
				<div class="card-body">
					<p>test</p>
				</div>
			</div>
		</div>
		<div class="col-6">
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
			</div>
		</div>
	</div>
</div>

<script>
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
			language: "id"
			// minimumInputLength: 2,
		})
		selectPacking.select2({
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
			language: "id"
			// minimumInputLength: 2,
		})
		selectSalePacking.select2({
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
			language: "id"
			// minimumInputLength: 2,
		})
		selectbuyPacking.select2({
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
			language: "id"
			// minimumInputLength: 2,
		})
	});
	const selectBrand = $('#brandProduct');
	const selectPacking = $('#defPacking');
	const selectSalePacking = $('#defSalePacking');
	const selectbuyPacking = $('#defBuyPacking');
	const variantInput = $('#variantInput');

	variantInput.on('itemAdded', function(event) {
		console.log(event.item)
	});
	variantInput.on('itemRemoved', function(event) {
		console.log(event.item)
	});

	const brandListUrl = <?= json_encode(base_url('master/getBrandList')) ?>;

	const baKey = <?= json_encode(ID_ROLE_BA); ?>;
	const remove = $('#btn_remove');
	const table = $('#table');
	const modal = $('#modal-form');
	const urlSave = 'saveUser';
	const urlDelete = 'deleteUser';
	const urlActiveNonactive = 'setActiveUser';
	const urlGetRoleList = 'getRoleList';
	const urlGetBrandList = '/simpati/master/getBrandList';
	const urlGetSingleUser = 'getSingleUser';
	const elBrandDiv = `<label>Merek yang dipegang</label>
						<div id="cbBrand"></div>`

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

	function fetchList(url, name, id, type, checked = null) {
		$.ajax({
			url: url,
			type: 'get',
			dataType: 'json',
			success: function(res) {
				if (res.status) {
					let data = res.data;
					createCheckbox(id, name, data, type);
				}
			},
			complete: function() {
				hideLoaderScreen();
				if (checked) {
					checked.forEach(brand => {
						$(`#cbBrand${brand}`).prop('checked', true);
					});
				}
			}
		})
	}

	function createCheckbox(id, name, data, type) {
		let el = ''
		data.forEach((cb) => {
			el += ` <div class="custom-control custom-checkbox">
						<input class="custom-control-input" id="${id+cb.id}" name="${name}" value="${cb.id}" type="checkbox">
						<label class="custom-control-label" for="${id+cb.id}">${cb.nama}</label>
					</div>`
		});
		$(`#${id}`).html(el);
		if (type === 'role') {
			$(`#cbRole${baKey}`).change(function() {
				if ($(this).is(':checked')) {
					showLoaderScreen();
					$(`#brandDiv`).html(elBrandDiv);
					fetchList(urlGetBrandList, 'brand_id[]', 'cbBrand', 'brand');
				} else {
					$(`#brandDiv`).html('');
				}
			})
		}
	}

	function newForm(isEdit = false) {
		modal.modal({
			backdrop: 'static',
			keyboard: false
		});
		let text = isEdit ? 'Edit User (Kosongkan Password jika tidak ingin mengubah password)' : 'Input Data User Baru';
		$('#modalHeader').text(text);
		$('#ff').trigger("reset");
	}

	function actionFormatter(val, row) {
		return `
		<div class="col-12 p-0 text-center">
        <div class="row d-flex justify-content-around">
            <button class="btn btn-primary btn-sm m-0 btn-action" data-toggle="tooltip" data-placement="top" title="Edit Menu" onclick="editForm(${row.id})"><span class="btn-inner--icon"><i class="fa fa-edit"></i></span></button>
            <button class="btn btn-danger btn-sm m-0 btn-action" data-toggle="tooltip" data-placement="top" onclick="destroy(${row.id})" title="Hapus Menu"><span class="btn-inner--icon"><i class="fa fa-trash"></i></span></button>
        </div>
        </div>
		`
	}

	function setDropdown(id, url, comp) {
		$.ajax({
			type: 'GET',
			url: `${url}?id=${id}`
		}).then(function(data) {
			data = data.items[0];
			let option = new Option(data.text, data.id, true, true);
			comp.append(option).trigger('change');
			comp.trigger({
				type: 'select2:select',
				params: {
					data: data
				}
			});
		});
	}
</script>
