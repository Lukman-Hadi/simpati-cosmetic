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
					<table id="table" data-toggle="table" data-toolbar="#toolbar" data-url="getListAllStock" data-pagination="true" data-search="true" data-click-to-select="false" class="table table-sm" data-side-pagination="server" data-page-list="[10,20,50,all]" data-show-refresh="true" data-show-columns="true" data-show-columns-toggle-all="true">
						<thead class="thead-light text-center">
							<tr>
								<!-- <th data-width="2" data-width-unit="%" data-checkbox="true"></th> -->
								<th data-field="product_name" data-sortable="true" data-width="25" data-width-unit="%" class="proper-case px-1">Nama Barang</th>
								<th data-field="product_code" class="px-1" data-width="10" data-width-unit="%">Kode Barang</th>
								<th data-field="brand_name" class="px-1" data-width="10" data-width-unit="%">Merek</th>
								<th data-field="list_variant" data-formatter="lowercaseRow" class="proper-case px-1" data-width="20" data-width-unit="%">Daftar Variant</th>
								<th data-field="total_unit" class="p-0" data-align="center" data-formatter="unitFormatter">Jumlah Unit</th>
								<th data-field="total_stock" data-sortable="true" class="p-0" data-align="center">Jumlah Total Barang</th>
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

	function lowercaseRow(val, row) {
		return val?val.toLowerCase():'-';
	}

	function unitFormatter(val, row) {
		if(!row.total_stock)return '-';
		let res = '';
		row.Pack.forEach(arr => {
			if (arr.total != 0) {
				res += ` ${arr.total} ${arr.unit}`
			}
		});
		return res;
	}
</script>
