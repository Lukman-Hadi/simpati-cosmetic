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
				<div class="card-body">
					<form action="<?= base_url('report/printdaftarbarang') ?>" method="POST" target="_blank">
						<div class="form-group">
							<label class="form-control-label" for="exampleFormControlInput1">Pilih Merk</label>
							<select id="brand" name="brand_id[]" class="form-control select2-multiple form-control brand-sel" multiple="multiple" required>
								<option></option>
							</select>
						</div>
						<div class="form-group text-right">
							<button class="btn btn-primary" type="submit"> Cari </button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
</div>

<script>
	const brandListUrl = <?= json_encode(base_url('master/getBrandListSelect')) ?>;
	$(document).ready(function() {
		$.fn.select2.defaults.set("theme", "bootstrap");
		hideLoaderScreen();
		$('.brand-sel').select2({
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
			placeholder: 'Cari Merk',
			language: "id"
			// minimumInputLength: 2,
		})
	});
</script>
