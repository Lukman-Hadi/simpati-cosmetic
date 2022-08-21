<style>
	.form-group {
		margin-bottom: 0.5rem;
	}

	#chechboxField .invalid-feedback {
		text-align: center;
	}

	.proper-case {
		text-transform: capitalize;
	}

	td {
		white-space: normal !important;
	}

	.input-group-text {
		padding: 0rem 1rem;
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
	<!-- Table -->
	<div class="row">
		<div class="col">
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
				<div id="toolbar">
					
				</div>
				<div class="table-responsive py-2 px-4">
					<table id="table" data-toggle="table" data-toolbar="#toolbar" data-url="getListStock" data-pagination="true" data-search="true" data-click-to-select="false" class="table table-sm" data-side-pagination="server" data-page-list="[10,20,50,all]" data-show-refresh="true" data-show-columns="true" data-show-columns-toggle-all="true">
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
							</tr>
						</thead>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modalView" tabindex="-1" role="dialog" aria-labelledby="modalView" aria-hidden="true">
	<div class="modal-dialog modal- modal-dialog-centered modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-body p-0">
				<div class="modal-header">
					<h5 class="modal-title proper-case" id="modalHeader"></h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="card bg-secondary border-0 mb-0">
					<div class="card-body px-lg-5 py-lg-2">
						<div class="row">
							<div class="col-9">
								<div class="row">
									<div class="col-3"><label class="form-control-label">Nama</label></div>
									<div class="col-9">
										<p id="productName"></p>
									</div>
								</div>
								<div class="row">
									<div class="col-3 text-left"><label class="form-control-label">Kode Barang</label></div>
									<div class="col-9">
										<p id="productCode"></p>
									</div>
								</div>
								<div class="row">
									<div class="col-3 text-left"><label class="form-control-label">Merk Barang</label></div>
									<div class="col-9">
										<p id="productBrand"></p>
									</div>
								</div>
								<div class="row">
									<div class="col-3 text-left"><label class="form-control-label">Harga Jual</label></div>
									<div class="col-9">
										<p id="productPrice"></p>
									</div>
								</div>
								<div class="row">
									<div class="col-3 text-left"><label class="form-control-label">Deskripsi</label></div>
									<div class="col-9">
										<p id="productDesc"></p>
									</div>
								</div>
							</div>
							<div class="col-3">
								<table class="table align-items-center table-sm table-flush" id="packingUnitTable">
									<thead class="thead-light">
										<th style="width: 5%;" class="p-1">Urutan</th>
										<th class="p-1">Kode Unit Kemasan</th>
									</thead>
									<tbody id="packingUnitTableBody">
										
									</tbody>
								</table>
							</div>
						</div>
						<p class="text-center">Variant Barang</p>
						<div class="row">
							<div class="table-responsive">
								<table class="table table-sm table-bordered" id="tableVariant">
									<thead class="thead-light">
										<th>Kode Varian</th>
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
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	const baKey = <?= json_encode(ID_ROLE_BA); ?>;
	const remove = $('#btn_remove');
	const table = $('#table');
	// const modal = $('#modal-form');
	const modal = $('#modalView');
	const urlSave = 'saveUser';
	const urlDelete = 'deleteUser';
	const urlActiveNonactive = 'setActiveUser';
	const urlGetRoleList = 'getRoleList';
	const urlGetBrandList = '/simpati/master/getBrandList';
	const urlGetSingleUser = 'getSingleUser';
	const elBrandDiv = `<label>Merek yang dipegang</label>
						<div id="cbBrand"></div>`

	const urlGetProduct = <?= json_encode(base_url('barang/getSingleProducts')) ?>;

	$(document).ready(function() {
		hideLoaderScreen();
		fetchList(urlGetRoleList, 'role_id[]', 'cbRole', 'role');
	});

	modal.on('hidden.bs.modal', function() {
		$('#modalHeader').text('');
		$('#productName').text('');
		$('#productCode').text('');
		$('#productBrand').text('');
		$('#productDesc').text('');
		$('#productPrice').text('');
		$('#packingUnitTableBody').html('');
		$('#variantTableBody').html('');
	})

	function sellValueFormatter(val, row) {
		let res = '';
		if (row.sell_method === '%') {
			res = `${Number(val)}%`;
		} else {
			res = uang.format(val);
		}
		if(row.stock_id == null) return '-';
		return `${res} / ${row.Pack[row.Pack.length-1].unit}`;
	}
	
	function unitFormatter(val,row){
		console.log({val,row})
		if(row.stock_id == null) return '-';
		let res = '';
		row.Pack.forEach(arr => {
			if(arr.total!=0){
				res += ` ${arr.total} ${arr.unit}` 
			}
		});
		return res;
	}

	function viewProduct(id) {
		ajaxCall(`${urlGetProduct}?id=${id}`).done(function(result) {
			hideLoaderScreen();
			if (result.status) {
				renderModalView(result.data);
			} else {
				Toast.fire({
					type: "error",
					title: result.message,
				});
			}
		}).fail(function() {
			Toast.fire({
				type: "error",
				title: "Terjadi kesalahan silahkan hubungi administrator",
			});
		})
	}

	function renderModalView(data) {
		console.log(data);
		$('#modalHeader').text(data.nama);
		$('#productName').text(data.nama);
		$('#productCode').text(data.productCode);
		$('#productBrand').text(data.brand);
		$('#productDesc').text(data.desc);
		let price = data.sellMethod == '%'?`${data.sellValue} ${data.sellMethod}`: `${data.sellMethod} ${data.sellValue}`;
		$('#productPrice').text(price);
		let htmlPack = '';
		data.productPack.forEach((pack,i) => {
			htmlPack += `<tr><td>${i+1}</td><td>${pack.unit}</td></tr>`
		});
		$('#packingUnitTableBody').html(htmlPack);
		let htmlVariant = '';
		data.productVariant.forEach(variant => {
			htmlVariant += `<tr><td>${variant.variantCode}</td><td>${variant.nama}</td><td>${variant.description}</td><td>${variant.limitReminder}</td></tr>`
		});
		$('#variantTableBody').html(htmlVariant);
		modal.modal({
			backdrop: 'static',
			keyboard: false
		});
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

	function newForm(isEdit = false) {
		modal.modal({
			backdrop: 'static',
			keyboard: false
		});
		let text = isEdit ? 'Edit User (Kosongkan Password jika tidak ingin mengubah password)' : 'Input Data User Baru';
		$('#modalHeader').text(text);
		$('#ff').trigger("reset");
	}

	// function actionFormatter(val, row) {
	// 	return `
	// 	<div class="col-12 p-0 text-center">
	//     <div class="row d-flex justify-content-around">
	//         <button class="btn btn-primary btn-sm m-0 btn-action p-0" data-toggle="tooltip" data-placement="top" title="Edit Menu" onclick="editForm(${row.id})"><span class="btn-inner--icon"><i class="fa fa-edit"></i></span></button>
	//         <button class="btn btn-danger btn-sm m-0 btn-action p-0" data-toggle="tooltip" data-placement="top" onclick="destroy(${row.id})" title="Hapus Menu"><span class="btn-inner--icon"><i class="fa fa-trash"></i></span></button>
	//     </div>
	//     </div>
	// 	`
	// }
	function actionFormatter(val, row) {
		return `
		<div class="col-12 p-0 text-center">
		<div class="row d-flex justify-content-center">
			<a href="#" class="badge badge-pill badge-secondary badge-sm" data-toggle="tooltip" data-placement="top" title="Lihat Detail Barang" onClick="viewProduct(${row.id})"><i class="fa fa-eye"></i></a>
			<a href="menuControl/${row.id}" class="badge badge-pill badge-primary badge-sm" data-toggle="tooltip" data-placement="top" title="Edit Barang"><i class="fa fa-edit"></i></a>
			<a href="menuControl/${row.id}" class="badge badge-pill badge-danger badge-sm" data-toggle="tooltip" data-placement="top" title="Hapus Barang"><i class="fa fa-trash"></i></a>
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
