<div class="header bg-primary pb-6">
	<div class="container-fluid">
		<div class="header-body">
			<div class="row align-items-center py-4">
				<div class="col-lg-6 col-7">
					<h6 class="h2 text-white d-inline-block mb-0"><?= $title ?></h6>
				</div>
				<div class="col-lg-6 col-5 col-md-12 text-right">
					<a href="<?= base_url('export/templateBarang') ?>" role="button" class="btn btn-secondary">Download Template Barang</a>
				</div>
			</div>
		</div>
	</div>
</div>
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
							<div class="col-12">
								<div class="form-group" id="nama">
									<label>Upload File <span class="mandatory">*</span></label>
									<input type="file" name="file" class="form-control" placeholder="cth: la tulipe lipstick ">
								</div>
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
	const form = $('#form');
	const urlSave = 'uploadFileBarang';
	$(document).ready(function() {
		hideLoaderScreen();
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
</script>
