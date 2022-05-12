<style>
	.form-group {
		margin-bottom: 0.5rem;
	}

	#chechboxField .invalid-feedback {
		text-align: center;
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
					<button class="btn btn-md btn-round btn-icon btn-primary" onclick="newForm()">
						<span class="btn-inner--icon"><i class="fas fa-plus"></i></span>
						<span class="btn-inner--text">Tambah Baru</span>
					</button>
					<button id="btn_remove" class="btn btn-md btn-danger btn-round btn-icon" data-toggle="tooltip" data-original-title="Hapus Data" onclick="destroyBatch()" disabled>
						<span class="btn-inner--icon"><i class="fas fa-trash"></i></span>
						<span class="btn-inner--text">Hapus</span>
					</button>
				</div>
				<div class="table-responsive py-2 px-4">
					<table id="table" data-toggle="table" data-toolbar="#toolbar" data-url="getUsers" data-pagination="true" data-search="true" data-click-to-select="false" class="table table-sm" data-side-pagination="server" data-page-list="[10,20,50,all]" data-show-refresh="true" data-show-columns="true" data-show-columns-toggle-all="true">
						<thead class="thead-light text-center">
							<tr>
								<th data-width="2" data-width-unit="%" data-checkbox="true"></th>
								<th data-field="nama" data-width="10" data-width-unit="%">Nama</th>
								<th data-field="username" data-width="10" data-width-unit="%">Username</th>
								<th data-field="user_role">Role</th>
								<th data-field="user_brand">Merek (Brand) yang dipegang</th>
								<th data-field="is_active" data-sortable="true" data-width="1" data-width-unit="%" data-formatter="statusFormatter">Status</th>
								<th data-field="action" data-width="10" data-width-unit="%" data-formatter="actionFormatter">Action</th>
							</tr>
						</thead>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
	<div class="modal-dialog modal- modal-dialog-centered modal-md" role="document">
		<div class="modal-content">
			<div class="modal-body p-0">
				<div class="modal-header">
					<h5 class="modal-title" id="modalHeader"></h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="card bg-secondary border-0 mb-0">
					<div class="card-body px-lg-5 py-lg-2">
						<form id="ff" method="post" enctype="multipart/form-data" class="needs-validation">
							<div class="form-group" id="username">
								<label>Username</label>
								<input type="text" name="username" class="form-control form-control-sm" placeholder="Username">
							</div>
							<div class="form-group" id="nama">
								<label>Nama</label>
								<input type="text" name="nama" class="form-control form-control-sm" placeholder="Nama Lengkap">
							</div>
							<div class="form-group" id="password">
								<label>Password</label>
								<!-- <input type="password" name="password" class="form-control form-control-sm" placeholder=""> -->
								<div class="input-group input-group-merge">
									<input name="password" class="form-control form-control-sm" placeholder="Password" type="password">
									<div class="input-group-append" onclick="togglePassword()">
										<span class="input-group-text"><i class="fas fa-eye"></i></span>
									</div>
								</div>
							</div>
							<div class="form-group" id="password_confirmation">
								<label>Konfirmasi Password</label>
								<div class="input-group input-group-merge">
									<input type="password" name="password_confirmation" class="form-control form-control-sm" placeholder="">
									<div class="input-group-append" onclick="togglePassword()">
										<span class="input-group-text"><i class="fas fa-eye"></i></span>
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="row" id="chechboxField">
									<div class="col-md-6 col-6" id="roleDiv">
										<label>Role User</label>
										<div id="cbRole" class="form-group">
											<!-- Role -->
										</div>
									</div>
									<div class="col-md-6 col-6" id="brandDiv">

									</div>
								</div>
							</div>
							<div class="text-center">
								<button id="btnSubmit" type="submit" class="btn btn-primary my-4">Simpan</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	$(document).ready(function() {
		hideLoaderScreen();
		fetchList(urlGetRoleList, 'role_id[]', 'cbRole', 'role');
	});
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
