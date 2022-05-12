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
				<div class="card-body">
					<div class="row">
						<div class="col-lg-6 col-md-6 col-sm-12">
							<div class="form-group">
								<label class="form-control-label" for="example3cols1Input">Pelanggan</label>
								<select id="pptk" name="id_pptk" class="form-control form-control-sm select2-single" required>
									<option></option>
								</select>
							</div>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-12">
							<div class="form-group">
								<label class="form-control-label" for="example3cols1Input">Rentang Tanggal</label>
								<select id="pptk" name="id_pptk" class="form-control form-control-sm select2-single" required>
									<option></option>
								</select>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12">
							<button class="btn btn-primary" type="submit">Cari</button>
							<button class="btn btn-primary" type="submit">Tambah Baru</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col">
			<div class="card">
				<div class="table-responsive py-2 px-4">
					<table id="table"></table>
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
					<h5 class="modal-title" id="exampleModalLabel">Input Data Menu Baru</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="card bg-secondary border-0 mb-0">
					<div class="card-body px-lg-5 py-lg-2">
						<form id="ff" method="post" enctype="multipart/form-data" class="needs-validation">
							<div class="form-group" id="nama">
								<label>Nama Menu</label>
								<input type="text" name="nama" class="form-control form-control-sm" placeholder="Judul Menu" required>
							</div>
							<div class="form-group" id="link">
								<label>Link Menu</label>
								<input type="text" name="link" class="form-control form-control-sm" placeholder="ex: lorem/dolor/sit" required>
							</div>
							<div class="form-group" id="icon">
								<label>Icon</label>
								<input type="text" name="icon" class="form-control form-control-sm" placeholder="ex: fas fa-xx" required>
							</div>
							<div class="form-group" id="parent_id">
								<label>Main Menu</label>
								<select id="main_menu" name="parent_id" class="form-control select2-single" required>
									<option></option>
								</select>
							</div>
							<div class="form-group" id="ordinal">
								<label>Urutan</label>
								<input type="number" name="ordinal" class="form-control form-control-sm" placeholder="Urutan Menu">
							</div>
							<div class="text-center">
								<button type="submit" class="btn btn-primary my-4">Submit</button>
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
		$.fn.select2.defaults.set("theme", "bootstrap");
		selectMenu.select2({
			ajax: {
				url: 'getMenuListTest',
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
			placeholder: 'Cari Menu',
			language: "id"
			// minimumInputLength: 2,
		})
	})
	const remove = $('#btn_remove');
	const table = $('#table');
	const selectMenu = $('#main_menu')
	const modal = $('#modal-form');

	modal.on('hidden.bs.modal', function() {
		$('input[name="id"]').remove();
	})

	function onSearch() {
		table.bootstrapTable({
			url: './setting/getMenu',
			pagination: true,
			sidePagination: 'server',
			search: true,
			columns: [{
				field: 'id',
				title: 'Item ID'
			}, {
				field: 'name',
				title: 'Item Name'
			}, {
				field: 'price',
				title: 'Item Price'
			}]
		})
	}


	function destroy(id) {
		if (id) {
			Swal.fire({
				title: 'Apakah Anda Yakin?',
				text: "Anda tidak bisa mengembalikan data ini",
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Ya hapus data',
				cancelButtonText: 'Batalkan'
			}).then((result) => {
				if (result.value) {
					$.post('destroymenu', {
						id: [id]
					}, function(result) {
						console.log(result);
						if (result.status) {
							Toast.fire({
								type: 'success',
								title: '' + result.message + '.'
							});
						} else {
							Toast.fire({
								type: 'error',
								title: '' + result.message + '.'
							})
						}
						table.bootstrapTable('refresh');
					}, 'json');
				}
			})
		}
	}

	function destroyBatch() {
		let row = table.bootstrapTable('getSelections');
		let data = row.map(r => r.id);
		if (data.length > 0) {
			Swal.fire({
				title: 'Apakah Anda Yakin?',
				text: "Anda tidak bisa mengembalikan data ini",
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Ya hapus data',
				cancelButtonText: 'Batalkan'
			}).then((result) => {
				if (result.value) {
					$.post('destroymenu', {
						id: data
					}, function(result) {
						console.log(result);
						if (result.status) {
							Toast.fire({
								type: 'success',
								title: '' + result.message + '.'
							});
						} else {
							Toast.fire({
								type: 'error',
								title: '' + result.message + '.'
							})
						}
						table.bootstrapTable('refresh');
					}, 'json');
				}
			})
		}
	}

	function aktif() {
		let row = $("#table").bootstrapTable('getSelections')[0];
		if (row) {
			Swal.fire({
				title: 'Are you sure?',
				text: "You won't be able to revert this!",
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Yes'
			}).then((result) => {
				if (result.value) {
					$.post('aktifmenu', {
						id: row._id
					}, function(result) {
						if (result.errorMsg) {
							Toast.fire({
								type: 'error',
								title: '' + result.errorMsg + '.'
							})
						} else {
							Toast.fire({
								type: 'success',
								title: '' + result.message + '.'
							})
							table.bootstrapTable('refresh');
						}
					}, 'json');
				}
			})
		}
	}

	function editForm(id) {
		$.ajax({
			url: `getSingleMenu?id=${id}`,
			type: 'get',
			dataType: 'json',
			success: function(res) {
				newForm();
				data = res.data[0];
				for (let [key, val] of Object.entries(data)) {
					$(`input[name=${key}]`).val(val);
				}
				$('#ff').append(`<input type="hidden" name="id" value="${data.id}" id="id_form">`)
				setDropdown(data.parent_id, 'getMenuListTest', selectMenu);
			}
		})
	}

	function newForm() {
		modal.modal({
			backdrop: 'static',
			keyboard: false
		});
		$('#ff').trigger("reset");
		selectMenu.val(null).trigger('change');
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

	function statusFormatter(val, row) {
		if (val == 1) {
			return `<a href="#" class="badge badge-pill badge-success" onclick="activeNonActive(${row.id}); return false;">Aktif</a>`
		} else {
			return `<a href="#" class="badge badge-pill badge-danger" onclick="activeNonActive(${row.id}); return false;">Non Aktif</a>`
		}
	}

	function activeNonActive(id) {
		$.post('activeNonActive', {
			id: id
		}, function(result) {
			console.log(result);
			if (result.status) {
				Toast.fire({
					type: 'success',
					title: '' + result.message + '.'
				});
			} else {
				Toast.fire({
					type: 'error',
					title: '' + result.message + '.'
				})
			}
			table.bootstrapTable('refresh');
		}, 'json');
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

	$('#ff').on('submit', function(e) {
		e.preventDefault();
		const string = $('#ff').serialize();
		$.ajax({
			type: "POST",
			url: 'savemenu',
			data: string,
			success: (result) => {
				result = eval('(' + result + ')');
				if (result.status) {
					Toast.fire({
						type: 'success',
						title: '' + result.message + '.'
					});
					$('#modal-form').modal('toggle');
					table.bootstrapTable('refresh');
				} else {
					if (result.message == 'validationError') {
						let err = result.data
						$('.invalid-feedback').remove();
						$('.is-invalid').remove();
						for (let [key, val] of Object.entries(err)) {
							$(`input[name=${key}]`).addClass("is-invalid")
							$(`#${key}`).append(`<div class="invalid-feedback" >${val}</div>`)
						}
						$(".form-control").addClass("is-valid")
					} else {
						Toast.fire({
							type: 'error',
							title: '' + result.message + '.'
						});
					}
				}
			},
		})
	})

	table.on('check.bs.table uncheck.bs.table ' +
		'check-all.bs.table uncheck-all.bs.table',
		function() {
			remove.prop('disabled', !table.bootstrapTable('getSelections').length)
			$('.btn-action').prop('disabled', table.bootstrapTable('getSelections').length);
		})
</script>
