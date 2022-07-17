const uang = new Intl.NumberFormat("ID-id", {
	style: "currency",
	currency: "IDR",
});

function rupiahFormatter(val){
	return uang.format(val);
}

function totalStockFormatter(val){
	return String(val)+' Unit';
}

function destroy(id) {
	if (id) {
		Swal.fire({
			title: "Apakah Anda Yakin?",
			text: "Anda tidak bisa mengembalikan data ini",
			showCancelButton: true,
			confirmButtonColor: "#3085d6",
			cancelButtonColor: "#d33",
			confirmButtonText: "Ya hapus data",
			cancelButtonText: "Batalkan",
		}).then((result) => {
			if (result.value) {
				showLoaderScreen();
				$.post(
					urlDelete,
					{
						id: [id],
					},
					function (result) {
						console.log(result);
						if (result.status) {
							Toast.fire({
								type: "success",
								title: "" + result.message + ".",
							});
						} else {
							Toast.fire({
								type: "error",
								title: "" + result.message + ".",
							});
						}
						table.bootstrapTable("refresh");
						hideLoaderScreen();
					},
					"json"
				);
			}
		});
	}
}

function destroyBatch() {
	let row = table.bootstrapTable("getSelections");
	let data = row.map((r) => r.id);
	if (data.length > 0) {
		Swal.fire({
			title: "Apakah Anda Yakin?",
			text: "Anda tidak bisa mengembalikan data ini",
			showCancelButton: true,
			confirmButtonColor: "#3085d6",
			cancelButtonColor: "#d33",
			confirmButtonText: "Ya hapus data",
			cancelButtonText: "Batalkan",
		}).then((result) => {
			if (result.value) {
				showLoaderScreen();
				$.post(
					urlDelete,
					{
						id: data,
					},
					function (result) {
						console.log(result);
						if (result.status) {
							Toast.fire({
								type: "success",
								title: "" + result.message + ".",
							});
						} else {
							Toast.fire({
								type: "error",
								title: "" + result.message + ".",
							});
						}
						table.bootstrapTable("refresh");
						hideLoaderScreen();
					},
					"json"
				);
			}
		});
	}
}
function statusFormatter(val, row) {
	if (val == 1) {
		return `<a href="#" class="badge badge-pill badge-success badge-sm" onclick="activeNonActive(${row.id}); return false;">Aktif</a>`;
	} else {
		return `<a href="#" class="badge badge-pill badge-danger badge-sm" onclick="activeNonActive(${row.id}); return false;">Non Aktif</a>`;
	}
}

function activeNonActive(id) {
	showLoaderScreen();
	$.post(
		urlActiveNonactive,
		{
			id: id,
		},
		function (result) {
			hideLoaderScreen();
			if (result.status) {
				Toast.fire({
					type: "success",
					title: "" + result.message + ".",
				});
			} else {
				Toast.fire({
					type: "error",
					title: "" + result.message + ".",
				});
			}
			table.bootstrapTable("refresh");
		},
		"json"
	);
}

$("#ff").on("submit", function (e) {
	showLoaderScreen();
	e.preventDefault();
	const string = $("#ff").serialize();
	removeClassValidation();
	loadingButton(true);
	$.ajax({
		type: "POST",
		url: urlSave,
		data: string,
		success: (result) => {
			if (result.status) {
				Toast.fire({
					type: "success",
					title: "" + result.message + ".",
				});
				$("#modal-form").modal("toggle");
				table.bootstrapTable("refresh");
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
			loadingButton(false);
			hideLoaderScreen();
		},
	});
});

function addClassValidation(key, val) {
	$(`input[name=${key}]`).addClass("is-invalid");
	$(`textarea[name=${key}]`).addClass("is-invalid");
	$(`select[name=${key}]`).addClass("is-invalid");
	$(`#${key} input:checkbox`).addClass("is-invalid");
	$(`#${key}`).append(`<div class="invalid-feedback d-block" >${val}</div>`);
}

function removeClassValidation() {
	$(".is-invalid").removeClass("is-invalid");
	$(".is-valid").removeClass("is-valid");
	$(".invalid-feedback").remove();
}

function showLoaderScreen() {
	$("#loader").fadeIn(500);
}

function ajaxCall(url) {
	return $.ajax({
		type: "GET",
		url,
		beforeSend: showLoaderScreen()
	});
}

function hideLoaderScreen() {
	$("#loader").fadeOut(500);
}

function loadingButton(isLoading) {
	if (isLoading) {
		$("#btnSubmit").prop("disabled", true);
		$("#btnSubmit").html("Loading...");
	} else {
		$("#btnSubmit").prop("disabled", false);
		$("#btnSubmit").html("Submit");
	}
}

table.on(
	"check.bs.table uncheck.bs.table " +
		"check-all.bs.table uncheck-all.bs.table",
	function () {
		remove.prop("disabled", !table.bootstrapTable("getSelections").length);
		$(".btn-action").prop(
			"disabled",
			table.bootstrapTable("getSelections").length
		);
	}
);
