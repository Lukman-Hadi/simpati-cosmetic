<div class="header bg-primary pb-6">
	<div class="container-fluid">
		<div class="header-body">
			<div class="row align-items-center py-4">
				<div class="col-lg-6 col-7">
					<h6 class="h2 text-white d-inline-block mb-0"><?= $title ?></h6>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="container-fluid mt--6">
	<div class="row">
		<div class="col">
			<div class="card">
				<!-- Card header -->
				<div class="card-header border-0">
					<div class="row">
						<div class="col-6">
							<h3 class="mb-0"><?= $subtitle ?></h3>
						</div>
					</div>
				</div>
				<!-- Light table -->
				<!-- <div class="table-responsive">
					<table class="table align-items-center table-sm">
						<thead class="thead-light">
							<th>Nama Menu</th>
							<th width="10%">Akses</th>
						</thead>
						<tbody>
							<tr>
								<td colspan="2" class="text-center">Pengaturan Aplikasi</td>
							</tr>
							<tr>
								<td>Menu Aplikasi</td>
								<td>Kasi Akses</td>
							</tr>
							<tr>
								<td colspan="2" class="text-center">Hak Akses</td>
							</tr>
							<tr>
								<td>Hak Akses Menu</td>
								<td>Kasi Akses</td>
							</tr>
							<tr>
								<td>Hak Akses Menu</td>
								<td>Kasi Akses</td>
							</tr>
						</tbody>
					</table>
				</div> -->
				<div class="table-responsive py-2 px-4">
					<table id="table" data-toggle="table" data-toolbar="#toolbar" data-url="<?= site_url('user/getRole') ?>" data-pagination="true" data-search="true" data-click-to-select="false" class="table table-sm" data-side-pagination="server" data-page-list="[10,20,50,all]" data-show-refresh="true" data-show-columns="true" data-show-columns-toggle-all="true">
						<thead class="thead-light text-center">
							<tr>
								<th data-width="2" data-width-unit="%" data-checkbox="true"></th>
								<th data-field="nama" data-width="30" data-width-unit="%">Nama Role</th>
								<th data-field="description" data-width="30" data-width-unit="%">Deskripsi</th>
								<th data-field="is_active" data-sortable="true" data-width="1" data-width-unit="%" data-formatter="isActiveFormmater">Status</th>
								<th data-field="action" data-width="10" data-width-unit="%" data-formatter="actionFormatter">Action</th>
							</tr>
						</thead>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function() {
		hideLoaderScreen();
	})
	const level = '<?= $this->uri->segment(3) ?>';

	function isActiveFormmater(val, row) {
		if (val == 1) {
			return `<a href="#" class="badge badge-pill badge-success">Aktif</a>`;
		} else {
			return `<a href="#" class="badge badge-pill badge-danger">Non Aktif</a>`;
		}
	}

	function kasi_akses() {

	}

	function actionFormatter(val, row) {
		return `
        <div class="row d-flex justify-content-around">
		<div class="col-12 p-0 text-center">
			<a href="menuControl/${row.id}" class="badge badge-pill badge-primary badge-lg">Atur Hak Akses Menu</a>
		</div>
		</div>`
		// <button class="btn btn-primary btn-sm m-0 btn-action" data-toggle="tooltip" data-placement="top" title="Edit Menu" onclick="editForm(${row.id})"><span class="btn-inner--icon"><i class="fa fa-edit"></i></span></button>
	}
</script>
