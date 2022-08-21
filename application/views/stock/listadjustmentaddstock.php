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
					<!-- <button id="btn_remove" class="btn btn-md btn-danger btn-round btn-icon" data-toggle="tooltip" data-original-title="Hapus Data" onclick="destroyBatch()" disabled>
						<span class="btn-inner--icon"><i class="fas fa-trash"></i></span>
						<span class="btn-inner--text">Hapus</span>
					</button> -->
				</div>
				<div class="table-responsive py-2 px-4">
					<table id="table" data-toggle="table" data-toolbar="#toolbar" data-url="getListAdjsutmentAddStock" data-pagination="true" data-search="true" data-click-to-select="false" class="table table-sm" data-side-pagination="server" data-page-list="[10,20,50,all]" data-show-refresh="true" data-show-columns="true" data-show-columns-toggle-all="true">
						<thead class="thead-light text-center">
							<tr>
								<th data-field="ref_no" data-width="10" data-width-unit="%" class="proper-case px-1">No Ref</th>
								<th data-field="method" class="px-1" data-width="10" data-width-unit="%">Metode Penyesuaian</th>
								<th data-field="type" class="px-1" data-width="10" data-width-unit="%">Tipe Penyesuaian</th>
								<th data-field="total_stock" class="px-1" data-formatter="totalStockFormatter" data-width="10" data-width-unit="%">Total Barang</th>
								<th data-field="total_price" class="px-1" data-formatter="rupiahFormatter" data-width="10" data-width-unit="%">Total Nominal Barang</th>
								<th data-field="created_at" data-sortable="true" class="px-1" data-width="10" data-width-unit="%">Tanggal</th>
								<th data-field="user_modified" data-width="15" data-width-unit="%" class="px-1">User</th>
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
	const table = $('#table');
	
	$(document).ready(function() {
		hideLoaderScreen();
	});

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
			<a href="listAdjustmentAddDetail/${row.id}" class="badge badge-pill badge-secondary badge-sm" data-toggle="tooltip" data-placement="top" title="Lihat Detail"><i class="fa fa-eye"></i></a>
		</div>
		</div>
		`
	}
</script>
