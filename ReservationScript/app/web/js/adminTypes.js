(function ($) {
	$(function () {
		if ($('#frmCreateType').length > 0) {
			$('#frmCreateType').validate();
		}
		if ($('#frmUpdateType').length > 0) {
			$('#frmUpdateType').validate();
		}
		$i18n_selector = $(".i18n_selector");
		if ($i18n_selector.length > 0) {
			$i18n_selector.i18n({
				numberOfLocales: 3,
				onSelect: function (inst, index) {
					var context = inst.elHolder.parent();
					$(":input[name^='i18n']", context).hide();
					$(":input[name^='i18n[" + index + "]']", context).show();
				}
			});
		}

		$("a.icon-delete").live("click", function (e) {
			e.preventDefault();
			$('#dialogDelete').data("id", $(this).attr('rel')).dialog('open');
		});
		
		if ($("#dialogDelete").length > 0) {
			$("#dialogDelete").dialog({
				autoOpen: false,
				resizable: false,
				draggable: false,
				height:220,
				modal: true,
				buttons: {
					'Delete': function() {
						$.ajax({
							type: "POST",
							data: {
								id: $(this).data("id")
							},
							url: "index.php?controller=AdminTypes&action=delete",
							success: function (res) {
								$("#content").html(res);
							}
						});
						$(this).dialog('close');			
					},
					'Cancel': function() {
						$(this).dialog('close');
					}
				}
			});
		}
	});
})(jQuery);