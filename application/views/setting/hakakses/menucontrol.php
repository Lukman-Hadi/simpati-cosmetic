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
				<form id="ff">
					<div class="table-responsive">
						<table class="table align-items-center table-sm" id="table">
							<thead class="thead-light">
								<th>Nama Menu</th>
								<th width="10%">Akses</th>
							</thead>
							<tbody>
								<?php 
									$html = '';
									foreach ($menus as $menu) {
										$html .= "<tr>
													<td>$menu->title</td>
													<td>
														<label class='custom-toggle'>
														<input type='checkbox' ".checked_akses($menu->access)." name='menu_id[]' value='".$menu->id."'>
														<span class='custom-toggle-slider rounded-circle' data-label-off='No' data-label-on='Yes'></span>
														</label>	
													</td>
												</tr>";
									}
									echo $html;
								?>
							</tbody>
						</table>
						<hr class="m-0">
					</div>
					<div class="card-body text-right">
						<div class="row">
							<div class="col-12 text-right">
								<button class="btn btn-icon btn-primary btn-md" type="submit" id="btnSubmit">
									<span class="btn-inner--icon"><i class="ni ni-send"></i></span>
									<span class="btn-inner--text">Simpan</span>
								</button>
							</div>
						</div>
					</div>
					<input type="hidden" name="role_id" value="<?=$roleId?>">
				</form>
			</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function() {
		hideLoaderScreen();
	})
	const table = $('#table');
	const urlSave = '<?=base_url('accessControl/saveAccessMenu')?>';

	function isActiveFormmater(val, row) {
		if (val == 1) {
			return `<a href="#" class="badge badge-pill badge-success">Aktif</a>`;
		} else {
			return `<a href="#" class="badge badge-pill badge-danger">Non Aktif</a>`;
		}
	}
</script>
