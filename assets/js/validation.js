$(".no-space-key").keydown(function (e) {
	console.log(e);
	if (e.keyCode === 32) e.preventDefault();
});
