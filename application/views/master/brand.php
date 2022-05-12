<div class="header bg-primary pb-6">
	<div class="container-fluid">
		<div class="header-body">
			<div class="row align-items-center py-4">
				<div class="col-lg-6 col-7">
					<h6 class="h2 text-white d-inline-block mb-0"><?= $title ?></h6>
				</div>
				<div class="col-lg-6 col-5 col-md-12 text-right">
					<!-- reserved -->
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
					<table id="table" data-toggle="table" data-toolbar="#toolbar" data-url="getBrand" data-pagination="true" data-search="true" data-click-to-select="false" class="table table-sm" data-side-pagination="server" data-page-list="[10,20,50,all]" data-show-refresh="true" data-show-columns="true" data-show-columns-toggle-all="true">
						<thead class="thead-light text-center">
							<tr>
								<th data-width="2" data-width-unit="%" data-checkbox="true"></th>
								<th data-field="nama" data-width="30" data-width-unit="%">Nama Merek</th>
								<th data-field="description" data-width="30" data-width-unit="%">Deskripsi</th>
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
					<h5 class="modal-title" id="modalHeader">
						<!-- header -->
					</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="card bg-secondary border-0 mb-0">
					<div class="card-body px-lg-5 py-lg-2">
						<form id="ff" method="post" enctype="multipart/form-data" class="needs-validation">
							<div class="form-group" id="nama">
								<label>Nama Merek</label>
								<input type="text" name="nama" class="form-control form-control-sm" placeholder="Nama Merek">
							</div>
							<div class="form-group" id="description">
								<label>Dekripsi Merek</label>
								<textarea name="description" id="taDescription" rows="7" class="form-control" resize="none"></textarea>
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
	})
	const remove = $('#btn_remove');
	const table = $('#table');
	const modal = $('#modal-form');
	const urlSave = 'saveBrand';
	const urlDelete = 'destroyBrand';
	const urlActiveNonactive = 'setActiveBrand';
	const urlSingleRow = 'getSingleBrand';

	modal.on('hidden.bs.modal', function() {
		$('input[name="id"]').remove();
	})

	function editForm(id) {
		showLoaderScreen();
		$.ajax({
			url: `${urlSingleRow}?id=${id}`,
			type: 'get',
			dataType: 'json',
			success: function(res) {
				newForm(true);
				data = res.data[0];
				for (let [key, val] of Object.entries(data)) {
					$(`input[name=${key}]`).val(val);
					$(`textarea[name=${key}]`).val(val);
				}
				$('#ff').append(`<input type="hidden" name="id" value="${data.id}" id="id_form">`)
				hideLoaderScreen();
			}
		})
	}

	function newForm(isEdit = false) {
		removeClassValidation();
		modal.modal({
			backdrop: 'static',
			keyboard: false
		});
		let text = isEdit ? 'Edit Merek' : 'Input Data Merek Baru';
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
</script>
