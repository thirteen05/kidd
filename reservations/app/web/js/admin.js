(function ($) {
	$(function () {
		if ($('#frmLoginAdmin').length > 0) {
			$('#frmLoginAdmin').validate({
				rules: {
					login_username: "required",
					login_password: "required"
				},
				errorContainer: $("#login-errors")
			});
		}
		var $frmUpdate = $("#frmUpdate");
		if ($frmUpdate.length > 0) {
			$frmUpdate.bind("submit", function (e) {
				$("input[type='submit']").prop("disabled", true);
			});
		}
	});
})(jQuery);